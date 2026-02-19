<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class FedexService
{

    protected $apiUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->clientId = config('services.fedex.client_id');
        $this->clientSecret = config('services.fedex.client_secret');

        if (config('services.fedex.mode') === 'sandbox') {
            $this->apiUrl = config('services.fedex.sandbox_url');
        } else {
            $this->apiUrl = config('services.fedex.live_url');
        }
    }

    public function getAccessToken(): string
    {
        return Cache::remember('fedex_access_token', 3500, function () {
            $payload = [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ];
            $response = Http::asForm()->timeout(60)->post("{$this->apiUrl}/oauth/token", $payload);

            $this->logRequest('oauth/token', $payload, $response);

            if ($response->failed()) {
                $response->throw();
            }

            return $response->json('access_token');
        });
    }

    public function createShipment($transaksi): array
    {
        $payload = $this->buildShipmentPayload($transaksi);

        $response = Http::withToken($this->getAccessToken())
            ->withHeaders(['Content-Type' => 'application/json'])
            ->timeout(60)
            ->post("{$this->apiUrl}/ship/v1/shipments", $payload);

        $this->logRequest('ship/v1/shipments', $payload, $response);

        if ($response->failed()) {
            $response->throw();
        }

        $responseBody = $response->json();
        $transactionShipment = $responseBody['output']['transactionShipments'][0] ?? [];

        $labelUrl = $transactionShipment['pieceResponses'][0]['packageDocuments'][0]['url'] ?? null;
        $trackingNumber = $transactionShipment['masterTrackingNumber'] ?? null;
        $rateData = $transactionShipment['shipmentRateData'] ?? [];
        $totalCharge = $rateData['totalNetCharge'] ?? 0;
        $currency = $rateData['currency'] ?? 'USD';

        if (!$labelUrl || !$trackingNumber) {
            throw new \Exception('Label URL or tracking number not found in FedEx response.');
        }

        $labelContent = $this->downloadLabel($labelUrl);
        $fileName = "{$trackingNumber}.pdf";
        $relativePath = "backend/assets/awb/{$fileName}";
        $absolutePath = public_path($relativePath);

        // Create directory if it doesn't exist
        $directory = dirname($absolutePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($absolutePath, $labelContent);
        $publicUrl = asset($relativePath);

        return [
            'trackingNumber' => $trackingNumber,
            'labelUrl' => $publicUrl,
            'labelPath' => $relativePath,
            'totalCharge' => $totalCharge,
            'currency' => $currency,
            'shipmentId' => $responseBody['jobId'] ?? null,
            'serviceType' => $payload['requestedShipment']['serviceType'],
            'rateResponse' => json_encode($rateData),
            'shipperAddress' => json_encode($payload['requestedShipment']['shipper']),
            'recipientAddress' => json_encode($payload['requestedShipment']['recipients'][0]),
            'weight' => $payload['requestedShipment']['requestedPackageLineItems'][0]['weight']['value'],
            'response' => $responseBody
        ];
    }

    public function downloadLabel(string $url): string
    {
        $response = Http::withToken($this->getAccessToken())->timeout(60)->get($url);

        $this->logRequest('downloadLabel', ['url' => $url], $response);

        if ($response->failed()) {
            $response->throw();
        }

        return $response->body();
    }

    public function logRequest(string $endpoint, array $request, \Illuminate\Http\Client\Response $response): void
    {
        \App\Models\FedexLog::create([
            'endpoint' => $endpoint,
            'request_json' => json_encode($request),
            'response_json' => $response->body(),
            'status_code' => $response->status(),
            'status' => $response->successful() ? 'success' : 'error',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function buildShipmentPayload($transaksi): array
    {
        $address = $transaksi->address;

        if (empty($address->zip_code)) {
            throw new \InvalidArgumentException('Recipient postal code is required for international shipments.');
        }

        $commodities = $transaksi->details->map(function ($item) {
            return [
                "description" => $item->produk->nama_produk,
                "countryOfManufacture" => "ID",
                "quantity" => $item->qty,
                "quantityUnits" => "PCS",
                "unitPrice" => [
                    "amount" => $item->harga,
                    "currency" => "USD"
                ],
                "customsValue" => [
                    "amount" => $item->harga * $item->qty,
                    "currency" => "USD"
                ],
                "weight" => [
                    "units" => "KG",
                    "value" => $item->produk->gros
                ]
            ];
        });

        $totalCustomsValue = $commodities->sum(function ($commodity) {
            return $commodity['customsValue']['amount'];
        });

        $recipientAddress = [
            "streetLines" => explode("\n", wordwrap($address->alamat, 35, "\n", true)),
            "city" => $address->city,
            "postalCode" => $address->zip_code,
            "countryCode" => $this->getCountryCode($address->country->toArray())
        ];

        $recipientContact = [
            "personName" => $transaksi->user->userDetail->nama,
            "emailAddress" => $transaksi->user->email,
            "phoneNumber" => $address->phone
        ];

        $payload = [
            "labelResponseOptions" => "URL_ONLY",
            "requestedShipment" => [
                "shipper" => [
                    "address" => [
                        "streetLines" => explode("\n", wordwrap(config('services.fedex.shipper.address'), 35, "\n", true)),
                        "city" => config('services.fedex.shipper.city'),
                        "postalCode" => config('services.fedex.shipper.postal_code'),
                        "countryCode" => config('services.fedex.shipper.country_code')
                    ],
                    "contact" => [
                        "personName" => config('services.fedex.shipper.name'),
                        "emailAddress" => config('services.fedex.shipper.email'),
                        "phoneNumber" => config('services.fedex.shipper.phone')
                    ]
                ],
                "recipients" => [
                    [
                        "address" => $recipientAddress,
                        "contact" => $recipientContact
                    ]
                ],
                "shipDatestamp" => now()->format('Y-m-d'),
                "serviceType" => "INTERNATIONAL_PRIORITY",
                "packagingType" => "YOUR_PACKAGING",
                "pickupType" => "USE_SCHEDULED_PICKUP",
                "blockInsightVisibility" => false,
                "shippingChargesPayment" => [
                    "paymentType" => "SENDER"
                ],
                "customsClearanceDetail" => [
                    "dutiesPayment" => [
                        "paymentType" => "SENDER"
                    ],
                    "importerOfRecord" => [
                        "address" => $recipientAddress,
                        "contact" => $recipientContact
                    ],
                    "customsValue" => [
                        "amount" => $totalCustomsValue,
                        "currency" => "USD"
                    ],
                    "commodities" => $commodities->all()
                ],
                "labelSpecification" => [
                    "labelFormatType" => "COMMON2D",
                    "imageType" => "PDF",
                    "labelStockType" => "PAPER_4X6"
                ],
                "requestedPackageLineItems" => [
                    [
                        "weight" => [
                            "units" => "KG",
                            "value" => $transaksi->details->sum(function ($detail) {
                                return $detail->gros * $detail->qty;
                            })
                        ]
                    ]
                ]
            ],
            "accountNumber" => [
                "value" => config('services.fedex.account_number')
            ]
        ];

        $hasCompleteCommodityData = $commodities->every(function ($commodity) {
            return !empty($commodity['customsValue']['amount']) && !empty($commodity['weight']['value']);
        });

        if ($hasCompleteCommodityData) {
            $payload['requestedShipment']['shipmentSpecialServices'] = [
                "specialServiceTypes" => ["ELECTRONIC_TRADE_DOCUMENTS"],
                "etdDetail" => [
                    "requestedDocumentTypes" => ["COMMERCIAL_INVOICE"]
                ]
            ];
        }

        return $payload;
    }

    private function getCountryCode(array $addressData): ?string
    {
        $keysToTry = ['countryCode', 'country_code', 'iso2', 'country'];

        foreach ($keysToTry as $key) {
            if (!empty($addressData[$key])) {
                if (is_array($addressData[$key]) && !empty($addressData[$key]['iso2'])) {
                    return strtoupper($addressData[$key]['iso2']);
                }
                if (is_string($addressData[$key])) {
                    return strtoupper($addressData[$key]);
                }
            }
        }

        return null;
    }

    public function uploadCommercialInvoice(string $trackingNumber, string $relativeFilePath)
    {
        $accessToken = $this->getAccessToken();
        $absoluteFilePath = public_path($relativeFilePath);
        $fileName = basename($absoluteFilePath);

        if (!file_exists($absoluteFilePath)) {
            throw new \Exception("File not found at path: {$absoluteFilePath}");
        }

        $documentPayload = [
            'referenceId' => $trackingNumber,
            'name' => $fileName,
            'contentType' => 'application/pdf',
            'meta' => [
                'imageType' => 'COMMERCIAL_INVOICE',
                'imageIndex' => 'IMAGE_1'
            ]
        ];

        $rulesPayload = [
            'workflowName' => 'ETDPreshipment'
        ];

        $requestPayload = [
            'document' => $documentPayload,
            'rules' => $rulesPayload,
            'file' => $relativeFilePath
        ];

        $response = Http::withToken($accessToken)
            ->asMultipart()
            ->timeout(60)
            ->post("{$this->apiUrl}/documents/v1/document", [
                [
                    'name'     => 'document',
                    'contents' => json_encode($documentPayload)
                ],
                [
                    'name'     => 'rules',
                    'contents' => json_encode($rulesPayload)
                ],
                [
                    'name'     => 'attachment',
                    'contents' => fopen($absoluteFilePath, 'r'),
                    'filename' => $fileName
                ]
            ]);

        $this->logRequest('documents/v1/document', $requestPayload, $response);

        if ($response->failed()) {
            $response->throw();
        }

        return $response->json();
    }
}
