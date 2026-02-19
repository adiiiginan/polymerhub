<?php

namespace App\Jobs;

use App\Models\FedexCommercialInvoice;
use App\Models\FedexTradeDocument;
use App\Models\FedexShipment;
use App\Services\FedexTradeDocumentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class UploadTradeDocuments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = 60;

    protected string $trackingNumber;
    protected int $fedexCommercialInvoiceId;

    public function __construct(string $trackingNumber, int $fedexCommercialInvoiceId)
    {
        $this->trackingNumber = $trackingNumber;
        $this->fedexCommercialInvoiceId = $fedexCommercialInvoiceId;
    }

    public function handle(FedexTradeDocumentService $fedexTradeDocumentService)
    {
        $context = [
            'tracking_number' => $this->trackingNumber,
            'fedex_commercial_invoice_id' => $this->fedexCommercialInvoiceId,
            'attempt' => $this->attempts(),
        ];

        Log::info('Starting Trade Document Upload Job', $context);

        try {
            $invoice = FedexCommercialInvoice::findOrFail($this->fedexCommercialInvoiceId);
            if (empty($invoice->pdf_path)) {
                throw new \Exception("pdf_path is empty for invoice ID {$invoice->id}");
            }

            $shipment = FedexShipment::where('tracking_number', $this->trackingNumber)->firstOrFail();

            $shipperAddress = json_decode($shipment->shipper_address, true);
            $recipientAddress = json_decode($shipment->recipient_address, true);

            $originCountryCode = $shipperAddress['address']['countryCode'] ?? null;
            $destinationCountryCode = $recipientAddress['address']['countryCode'] ?? null;

            if (empty($originCountryCode) || empty($destinationCountryCode)) {
                throw new \InvalidArgumentException('Origin or Destination country code is missing from shipment data.');
            }

            Log::info('Extracted Country Codes for FedEx Upload', $context + [
                'origin' => $originCountryCode,
                'destination' => $destinationCountryCode
            ]);

            $response = $fedexTradeDocumentService->uploadCommercialInvoice(
                $this->trackingNumber,
                $invoice->pdf_path,
                $originCountryCode,
                $destinationCountryCode
            );

            FedexTradeDocument::updateOrCreate(
                [
                    'shipment_id'   => $shipment->id,
                    'document_type' => 'COMMERCIAL_INVOICE',
                ],
                [
                    'fedex_commercial_invoice_id' => $invoice->id,
                    'file_path'         => $invoice->pdf_path,
                    'upload_status'     => 'uploaded',
                    'fedex_document_id' => $response['output']['meta']['documentId'] ?? null,
                    'uploaded_at'       => now(),
                    'error_message'     => null,
                ]
            );

            $invoice->update(['status' => 'uploaded']);

            Log::info('Trade Document Upload Successful', $context);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $responseBody = $e->response->json();
            if ($e->response->status() === 404 && ($responseBody['code'] ?? null) === 'NOT.FOUND.ERROR') {
                Log::warning('FedEx shipment not found (404), retrying job.', $context + [
                    'release_delay' => 60,
                    'response' => $responseBody
                ]);
                if ($this->attempts() < $this->tries) {
                    $this->release($this->backoff);
                    return;
                }
            }
            $this->handleFailure($e, $context);
        } catch (Throwable $e) {
            $this->handleFailure($e, $context);
        }
    }

    protected function handleFailure(Throwable $e, array $context): void
    {
        Log::error('Trade Document Upload Failed', $context + [
            'error' => $e->getMessage(),
        ]);

        $shipment = FedexShipment::where('tracking_number', $this->trackingNumber)->first();

        if ($shipment) {
            FedexTradeDocument::updateOrCreate(
                [
                    'shipment_id'   => $shipment->id,
                    'document_type' => 'COMMERCIAL_INVOICE',
                ],
                [
                    'fedex_commercial_invoice_id' => $this->fedexCommercialInvoiceId,
                    'upload_status' => 'failed',
                    'error_message' => substr($e->getMessage(), 0, 255),
                ]
            );
        }

        FedexCommercialInvoice::where('id', $this->fedexCommercialInvoiceId)
            ->update(['status' => 'failed']);

        $this->fail($e);
    }

    public function failed(Throwable $exception): void
    {
        Log::critical('FATAL: FedEx Upload Job failed permanently after all retries.', [
            'tracking_number' => $this->trackingNumber,
            'fedex_commercial_invoice_id' => $this->fedexCommercialInvoiceId,
            'error' => $exception->getMessage(),
        ]);
    }
}
