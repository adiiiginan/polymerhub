<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class FedexTradeDocumentService
{
    protected FedexService $fedexService;
    protected string $apiUrl;

    public function __construct(FedexService $fedexService)
    {
        $this->fedexService = $fedexService;
        $this->apiUrl = config('services.fedex.mode') === 'sandbox'
            ? config('services.fedex.sandbox_url')
            : config('services.fedex.live_url');
    }

    /**
     * Uploads a Commercial Invoice to the FedEx Trade Documents API.
     *
     * @param string $trackingNumber
     * @param string $filePath
     * @param string $originCountryCode
     * @param string $destinationCountryCode
     * @return array
     * @throws \Exception
     */
    public function uploadCommercialInvoice(
        string $trackingNumber,
        string $filePath,
        string $originCountryCode,
        string $destinationCountryCode
    ): array {
        // 1. Validasi input
        if (empty($trackingNumber)) {
            throw new InvalidArgumentException('Tracking number cannot be empty.');
        }
        if (empty($originCountryCode)) {
            throw new InvalidArgumentException('Origin country code cannot be empty.');
        }
        if (empty($destinationCountryCode)) {
            throw new InvalidArgumentException('Destination country code cannot be empty.');
        }

        $absolutePath = public_path($filePath);
        if (!file_exists($absolutePath) || !is_readable($absolutePath)) {
            throw new \Exception("File does not exist or is not readable at: {$absolutePath}");
        }

        // 2. Buat payload dokumen dinamis
        $documentPayload = [
            'referenceId' => $trackingNumber,
            'name' => basename($absolutePath),
            'contentType' => 'application/pdf',
            'meta' => [
                'imageType' => 'COMMERCIAL_INVOICE',
                'imageIndex' => 'IMAGE_1',
                // Data dinamis tambahan bisa diletakkan di sini jika diperlukan oleh API
                // 'originCountryCode' => strtoupper($originCountryCode),
                // 'destinationCountryCode' => strtoupper($destinationCountryCode),
            ]
        ];

        $rulesPayload = [
            'workflowName' => 'ETDPreshipment'
        ];

        // 3. Logging sebelum request
        Log::info('Attempting to upload FedEx trade document.', [
            'tracking_number' => $trackingNumber,
            'origin_country' => $originCountryCode,
            'destination_country' => $destinationCountryCode,
            'file_path' => $filePath,
            'payload' => $documentPayload
        ]);

        // 4. Kirim request multipart
        $response = Http::withToken($this->fedexService->getAccessToken())
            ->asMultipart()
            ->timeout(60)
            ->post("{$this->apiUrl}/documents/v1/etd/upload", [
                [
                    'name'     => 'document',
                    'contents' => json_encode($documentPayload)
                ],
                [
                    'name'     => 'attachment',
                    'contents' => fopen($absolutePath, 'r'),
                    'filename' => basename($absolutePath)
                ]
            ]);

        // 5. Logging setelah request
        $this->fedexService->logRequest('documents/v1/etd/upload', [
            'document' => $documentPayload,
            'file' => $filePath
        ], $response);

        // 6. Handle response
        if ($response->failed()) {
            Log::error('FedEx trade document upload failed.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $response->throw();
        }

        Log::info('FedEx trade document uploaded successfully.', [
            'tracking_number' => $trackingNumber,
            'response' => $response->json()
        ]);

        return $response->json();
    }
}
