<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedexTradeDocumentService
{
    protected FedexService $fedexService;
    protected string $apiUrl;

    public function __construct(FedexService $fedexService)
    {
        $this->fedexService = $fedexService;

        // FIX #5 — Document/ETD API punya host BERBEDA dari API FedEx lainnya
        // (rate, shipment, oauth token pakai apis-sandbox.fedex.com / apis.fedex.com,
        // tapi Document API pakai documentapitest.prod.fedex.com / documentapi.prod.fedex.com).
        // Sebelumnya service ini salah reuse config('services.fedex.sandbox_url') / live_url,
        // sehingga request selalu dikirim ke host yang salah dan FedEx membalas 404 NOT.FOUND.ERROR.
        $this->apiUrl = config('services.fedex.mode') === 'sandbox'
            ? config('services.fedex.document_sandbox_url')
            : config('services.fedex.document_live_url');
    }

    /**
     * Upload commercial invoice ke FedEx ETD API.
     *
     * FIX #1 — Signature disesuaikan dengan pemanggil di UploadTradeDocuments.php:
     *           tambah $trackingNumber dan $shipmentDate.
     * FIX #2 — Duplicate key 'contentType' dihapus; pakai mime_content_type() agar dinamis.
     * FIX #3 — Content-Type attachment tidak lagi hardcoded 'application/pdf'.
     * FIX #4 — workflowName diperbaiki menjadi 'ETDPreshipment' (huruf kecil 's').
     * FIX #5 — Base URL diarahkan ke Document API host yang benar (lihat constructor).
     */
    public function uploadCommercialInvoice(
        string $trackingNumber,
        string $filePath,
        string $originCountryCode,
        string $destinationCountryCode,
        string $shipmentDate = ''
    ): array {
        $absolutePath = public_path($filePath);

        if (!file_exists($absolutePath) || !is_readable($absolutePath)) {
            throw new \Exception("File does not exist or is not readable at: {$absolutePath}");
        }

        // FIX #2 & #3 — deteksi MIME type sekali, pakai di dua tempat
        $mimeType = mime_content_type($absolutePath);

        $documentPayload = [
            'workflowName' => 'ETDPreshipment',   // FIX #4: 's' huruf kecil
            'carrierCode'  => 'FDXE',
            'name'         => basename($absolutePath),
            'contentType'  => $mimeType,           // FIX #2: satu nilai, dinamis
            'meta'         => [
                'shipDocumentType'       => 'COMMERCIAL_INVOICE',
                'trackingNumber'         => $trackingNumber,
                'shipmentDate'           => $shipmentDate
                    ? $shipmentDate . 'T00:00:00'
                    : now()->format('Y-m-d\T00:00:00'),
                'originCountryCode'      => strtoupper($originCountryCode),
                'destinationCountryCode' => strtoupper($destinationCountryCode),
            ],
        ];

        Log::info('FedexTradeDocumentService: Attempting ETD upload.', [
            'tracking_number'  => $trackingNumber,
            'file_path'        => $filePath,
            'mime_type'        => $mimeType,
            'api_url'          => $this->apiUrl,
            'document_payload' => $documentPayload,
        ]);

        $response = Http::withToken($this->fedexService->getAccessToken())
            ->asMultipart()
            ->timeout(60)
            ->post("{$this->apiUrl}/documents/v1/etds/upload", [
                [
                    'name'     => 'document',
                    'contents' => json_encode($documentPayload),
                    'headers'  => ['Content-Type' => 'application/json'],
                ],
                [
                    'name'     => 'attachment',
                    'contents' => fopen($absolutePath, 'r'),
                    'filename' => basename($absolutePath),
                    'headers'  => ['Content-Type' => $mimeType], // FIX #3: dinamis
                ],
            ]);

        $this->fedexService->logRequest('documents/v1/etds/upload', [
            'document' => $documentPayload,
            'file'     => $filePath,
        ], $response);

        if ($response->failed()) {
            Log::error('FedexTradeDocumentService: ETD upload failed.', [
                'status'          => $response->status(),
                'body'            => $response->body(),
                'tracking_number' => $trackingNumber,
                'api_url'         => $this->apiUrl,
            ]);
            $response->throw();
        }

        Log::info('FedexTradeDocumentService: ETD upload success.', [
            'tracking_number' => $trackingNumber,
            'response'        => $response->json(),
        ]);

        return $response->json();
    }
}
