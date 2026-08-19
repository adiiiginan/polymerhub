<?php

namespace App\Jobs;

use App\Models\FedexCommercialInvoice;
use App\Models\FedexTradeDocument;
use App\Services\FedexService;
use App\Services\FedexTradeDocumentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UploadTradeDocuments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trackingNumber;
    protected $commercialInvoiceId;
    protected $fedexDocumentId;

    /**
     * Jumlah percobaan ulang jika job gagal.
     */
    public int $tries = 3;

    /**
     * Jeda antar retry dalam detik.
     */
    public int $backoff = 60;

    public function __construct($trackingNumber, $commercialInvoiceId, $fedexDocumentId = null)
    {
        $this->trackingNumber      = $trackingNumber;
        $this->commercialInvoiceId = $commercialInvoiceId;
        $this->fedexDocumentId     = $fedexDocumentId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $fedexService    = new FedexService();
            $tradeDocService = new FedexTradeDocumentService($fedexService);

            $commercialInvoice = FedexCommercialInvoice::with([
                'tradeDocument',
                'order.address.country',
            ])->findOrFail($this->commercialInvoiceId);

            // Validasi file tersedia
            if (!$commercialInvoice->pdf_path || !file_exists(public_path($commercialInvoice->pdf_path))) {
                throw new \Exception("PDF file not found at: {$commercialInvoice->pdf_path}");
            }

            // Ambil destination country code
            $destinationCountryCode = $commercialInvoice->order->address->country->iso2
                ?? $commercialInvoice->order->address->country->country_code
                ?? 'US';

            Log::info("UploadTradeDocuments: Starting upload for tracking {$this->trackingNumber}", [
                'fedex_document_id'   => $this->fedexDocumentId,
                'pdf_path'            => $commercialInvoice->pdf_path,
                'destination_country' => $destinationCountryCode,
            ]);

            // Upload dokumen — signature sekarang cocok dengan FedexTradeDocumentService
            $response = $tradeDocService->uploadCommercialInvoice(
                $this->trackingNumber,
                $commercialInvoice->pdf_path,
                'ID',                           // origin selalu Indonesia
                $destinationCountryCode,
                $commercialInvoice->invoice_date->format('Y-m-d')
            );

            Log::info("UploadTradeDocuments: FedEx response received.", ['response' => $response]);

            // FIX #6 — Path ekstraksi docId disesuaikan dengan struktur response FedEx
            // yang sebenarnya, dikonfirmasi dari dokumentasi resmi:
            //
            // {
            //   "output": {
            //     "meta": {
            //       "documentType": "CO",
            //       "docId": "090493e181586308",
            //       "folderId": "0b0493e1812f8921"
            //     }
            //   },
            //   "customerTransactionId": "XXXX_XXX123XXXXX"
            // }
            //
            // Sebelumnya kode mencari 'output.locator.id' / 'output.documentId' yang
            // tidak pernah ada di response asli FedEx, sehingga docId selalu null
            // (masalah ini baru akan terlihat setelah base URL Document API diperbaiki,
            // karena sebelumnya request selalu gagal duluan dengan 404).
            $uploadedDocumentId    = $this->fedexDocumentId
                ?? $response['output']['meta']['docId']
                ?? null;

            $folderId              = $response['output']['meta']['folderId'] ?? null;
            $documentType          = $response['output']['meta']['documentType'] ?? null;
            $customerTransactionId = $response['customerTransactionId'] ?? null;

            if (!$uploadedDocumentId) {
                Log::warning("UploadTradeDocuments: docId tidak ditemukan di response.", [
                    'response' => $response,
                ]);
            }

            Log::info("UploadTradeDocuments: Document metadata extracted.", [
                'doc_id'          => $uploadedDocumentId,
                'folder_id'       => $folderId,
                'document_type'   => $documentType,
                'customer_txn_id' => $customerTransactionId,
            ]);

            // Update atau buat FedexTradeDocument
            $tradeDocument = FedexTradeDocument::where('fedex_commercial_invoice_id', $this->commercialInvoiceId)
                ->firstOrCreate(
                    ['fedex_commercial_invoice_id' => $this->commercialInvoiceId],
                    [
                        'shipment_id'   => $commercialInvoice->shipment_id,
                        'document_type' => 'CO',
                        'file_path'     => $commercialInvoice->pdf_path,
                        'upload_status' => 'pending',
                    ]
                );

            // Simpan folder_id & document_type juga jika kolomnya tersedia di tabel.
            // Jika kolom belum ada, cukup hapus dua baris update() ini atau tambahkan
            // migration untuk kolom fedex_folder_id / fedex_document_type.
            $tradeDocument->fill([
                'fedex_folder_id'     => $folderId,
                'fedex_document_type' => $documentType,
            ]);

            $tradeDocument->markAsUploaded($uploadedDocumentId);

            $commercialInvoice->update([
                'status'            => 'uploaded',
                'fedex_document_id' => $uploadedDocumentId,
            ]);

            Log::info("UploadTradeDocuments: Success", [
                'tracking_number'       => $this->trackingNumber,
                'commercial_invoice_id' => $this->commercialInvoiceId,
                'fedex_document_id'     => $uploadedDocumentId,
            ]);
        } catch (\Exception $e) {
            Log::error("UploadTradeDocuments: Failed", [
                'tracking_number'       => $this->trackingNumber,
                'commercial_invoice_id' => $this->commercialInvoiceId,
                'error'                 => $e->getMessage(),
                'trace'                 => $e->getTraceAsString(),
            ]);

            $tradeDocument = FedexTradeDocument::where('fedex_commercial_invoice_id', $this->commercialInvoiceId)->first();
            if ($tradeDocument) {
                $tradeDocument->markAsFailed($e->getMessage());
            }

            // Re-throw agar Laravel queue bisa retry sesuai $tries
            throw $e;
        }
    }

    /**
     * Handle job failure permanen (setelah semua retry habis).
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("UploadTradeDocuments: Job failed permanently after all retries.", [
            'tracking_number'       => $this->trackingNumber,
            'commercial_invoice_id' => $this->commercialInvoiceId,
            'error'                 => $exception->getMessage(),
        ]);

        $tradeDocument = FedexTradeDocument::where('fedex_commercial_invoice_id', $this->commercialInvoiceId)->first();
        if ($tradeDocument && $tradeDocument->upload_status === 'pending') {
            $tradeDocument->markAsFailed("Job failed permanently: {$exception->getMessage()}");
        }
    }
}
