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

        Log::info('FedexService constructed', [
            'client_id' => $this->clientId,
            'client_secret' => substr($this->clientSecret, 0, 5) . '...',
            'mode' => config('services.fedex.mode'),
            'api_url' => $this->apiUrl
        ]);
    }

    public function getAccessToken(): string
    {
        try {

            Log::info('FedEx Get Token Start', [
                'url' => "{$this->apiUrl}/oauth/token",
                'client_id' => $this->clientId,
            ]);

            $payload = [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ];

            $response = Http::asForm()
                ->withoutVerifying()
                ->timeout(120)
                ->connectTimeout(60)
                ->post(
                    "{$this->apiUrl}/oauth/token",
                    $payload
                );

            Log::info('FedEx Token Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->failed()) {

                Log::error('FedEx Token Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception(
                    'FedEx OAuth Error: ' . $response->body()
                );
            }

            $token = $response->json('access_token');

            if (!$token) {
                throw new \Exception('FedEx access token kosong.');
            }

            return $token;
        } catch (\Throwable $e) {

            Log::error('FedEx Get Token Exception', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            throw $e;
        }
    }

    public function createShipment($transaksi, ?string $docId = null): array
    {
        $payload = $this->buildShipmentPayload($transaksi, $docId);

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

        // ========================================================
        // CAPTURE output.meta dari FedEx response
        // ========================================================
        $outputMeta = $responseBody['output']['meta'] ?? null;
        $customerTransactionId = $responseBody['customerTransactionId'] ?? null;

        Log::info('FedEx Shipment Created - Commercial Document Metadata', [
            'trackingNumber' => $trackingNumber,
            'output.meta' => $outputMeta,
            'customerTransactionId' => $customerTransactionId,
        ]);

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
            'response' => $responseBody,
            // ========================================================
            // TAMBAHAN: output.meta dan customerTransactionId
            // ========================================================
            'output' => [
                'meta' => $outputMeta,
            ],
            'customerTransactionId' => $customerTransactionId,
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

    private function buildShipmentPayload($transaksi, ?string $docId = null): array
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
                                return $detail->gros;
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
            $etdDetail = [
                'requestedDocumentTypes' => ['COMMERCIAL_INVOICE'],
            ];

            // Jika ada docId dari pre-shipment upload, sertakan
            if ($docId) {
                $etdDetail['attachedDocuments'] = [
                    ['documentId' => $docId]
                ];
            }

            $payload['requestedShipment']['shipmentSpecialServices'] = [
                'specialServiceTypes' => ['ELECTRONIC_TRADE_DOCUMENTS'],
                'etdDetail'           => $etdDetail,
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

    public function getRates(array $data): array
    {
        try {
            Log::info('FedexService->getRates: Start', ['data' => $data]);

            // ---------------------------------------------------------
            // 1. Validasi data masuk
            // ---------------------------------------------------------
            $totalWeight = (float) ($data['totalWeight'] ?? 0);
            $destZip     = trim($data['destinationZip']     ?? '');
            $destCountry = strtoupper(trim($data['destinationCountry'] ?? ''));

            if ($totalWeight <= 0) {
                throw new \InvalidArgumentException('totalWeight harus lebih dari 0.');
            }
            if (empty($destZip)) {
                throw new \InvalidArgumentException('destinationZip tidak boleh kosong.');
            }
            if (empty($destCountry) || strlen($destCountry) !== 2) {
                throw new \InvalidArgumentException('destinationCountry harus berformat ISO 2 huruf (contoh: US, SG, AU).');
            }

            Log::info('FedexService->getRates: Validation passed', [
                'totalWeight' => $totalWeight,
                'destZip'     => $destZip,
                'destCountry' => $destCountry,
            ]);

            // ---------------------------------------------------------
            // 2. Ambil access token
            // ---------------------------------------------------------
            $accessToken = $this->getAccessToken();
            Log::info('FedexService->getRates: Access token retrieved');

            // ---------------------------------------------------------
            // 3. Bangun payload — sesuai request yang berhasil
            // ---------------------------------------------------------
            $shipperPostal   = config('services.fedex.shipper.postal_code');
            $shipperCountry  = strtoupper(config('services.fedex.shipper.country_code'));
            $shipperCity     = config('services.fedex.shipper.city');
            $shipperAddress  = config('services.fedex.shipper.address');
            $accountNumber   = config('services.fedex.account_number');

            $destCity        = trim($data['destinationCity']   ?? '');
            $destStreet      = trim($data['destinationStreet'] ?? '');
            $totalValue      = (float) ($data['totalValue']    ?? 1);
            $items           = $data['items']                  ?? [];

            // Helper: split alamat jadi maks 2 baris @ 35 karakter
            $splitStreet = function (?string $addr): array {
                if (!$addr) return [];
                $wrapped = wordwrap($addr, 35, "\n", true);
                return array_slice(explode("\n", $wrapped), 0, 2);
            };

            // Build packageLineItems dengan dimensions (sama seperti FedExController)
            $packageLineItems = [];
            foreach ($items as $item) {
                $packageLineItems[] = [
                    'weight' => [
                        'units' => 'KG',
                        'value' => (float) ($item['gros'] ?? 0) * (int) ($item['qty'] ?? 1),
                    ],
                    'dimensions' => [
                        'length' => (int) round($item['length'] ?? 1),
                        'width'  => (int) round($item['width']  ?? 1),
                        'height' => (int) round($item['height'] ?? 1),
                        'units'  => 'CM',
                    ],
                ];
            }

            // Fallback jika items kosong
            if (empty($packageLineItems)) {
                $packageLineItems[] = [
                    'weight' => [
                        'units' => 'KG',
                        'value' => $totalWeight,
                    ],
                ];
            }

            // ---------------------------------------------------------
            // 3a. Deteksi domestik vs internasional
            // ---------------------------------------------------------
            $isInternational = $destCountry !== $shipperCountry;

            Log::info('FedexService->getRates: International check', [
                'shipperCountry'   => $shipperCountry,
                'destCountry'      => $destCountry,
                'isInternational'  => $isInternational,
            ]);

            $requestedShipment = [
                'shipper' => [
                    'address' => [
                        'streetLines' => $splitStreet($shipperAddress),
                        'city'        => $shipperCity,
                        'postalCode'  => $shipperPostal,
                        'countryCode' => $shipperCountry,
                    ],
                ],
                'recipient' => [
                    'address' => [
                        'streetLines' => $splitStreet($destStreet),
                        'city'        => $destCity,
                        'postalCode'  => $destZip,
                        'countryCode' => $destCountry,
                    ],
                ],
                'preferredCurrency' => 'USD',
                'rateRequestType'   => ['ACCOUNT', 'LIST'],
                'shipDateStamp'     => now()->format('Y-m-d'),
                'pickupType'        => 'DROPOFF_AT_FEDEX_LOCATION',
                'packagingType'     => 'YOUR_PACKAGING',

                // Ikutin struktur contoh FedEx — paymentType di luar payor
                'shippingChargesPayment' => [
                    'payor' => [
                        'responsibleParty' => [
                            'address' => [
                                'streetLines' => $splitStreet($shipperAddress),
                                'city'        => $shipperCity,
                                'postalCode'  => $shipperPostal,
                                'countryCode' => $shipperCountry,
                                'residential' => false,
                            ],
                            'accountNumber' => [
                                'value' => $accountNumber,
                            ],
                        ],
                    ],
                    'paymentType' => 'SENDER', // ← di luar payor, bukan di dalam
                ],

                'requestedPackageLineItems' => $packageLineItems,
            ];

            // ---------------------------------------------------------
            // 3b. customsClearanceDetail — HANYA untuk shipment internasional
            // (ini yang sebelumnya hilang dan menyebabkan
            // RATE.CUSTOMCLEARANCEDETAIL.INVALID)
            // ---------------------------------------------------------
            if ($isInternational) {
                $commodities = [];

                foreach ($items as $item) {
                    $qty          = (int) ($item['qty'] ?? 1);
                    $weightPerQty = (float) ($item['gros'] ?? 0);
                    $unitPrice    = (float) ($item['price'] ?? ($totalValue / max(count($items), 1)));

                    $commodities[] = [
                        'description'         => trim($item['description'] ?? $item['name'] ?? 'General Merchandise'),
                        'name'                 => trim($item['name'] ?? 'General Merchandise'),
                        'countryOfManufacture' => $item['countryOfManufacture'] ?? 'ID',
                        'quantity'             => $qty,
                        'quantityUnits'        => 'PCS',
                        'numberOfPieces'       => $qty,
                        'unitPrice' => [
                            'amount'   => round($unitPrice, 2),
                            'currency' => 'USD',
                        ],
                        'customsValue' => [
                            'amount'   => round($unitPrice * $qty, 2),
                            'currency' => 'USD',
                        ],
                        'weight' => [
                            'units' => 'KG',
                            'value' => $weightPerQty * $qty,
                        ],
                    ];
                }

                // Fallback kalau items kosong / tidak lengkap
                if (empty($commodities)) {
                    $commodities[] = [
                        'description'         => 'General Merchandise',
                        'name'                 => 'General Merchandise',
                        'countryOfManufacture' => 'ID',
                        'quantity'             => 1,
                        'quantityUnits'        => 'PCS',
                        'numberOfPieces'       => 1,
                        'unitPrice' => [
                            'amount'   => round($totalValue, 2),
                            'currency' => 'USD',
                        ],
                        'customsValue' => [
                            'amount'   => round($totalValue, 2),
                            'currency' => 'USD',
                        ],
                        'weight' => [
                            'units' => 'KG',
                            'value' => $totalWeight,
                        ],
                    ];
                }

                $requestedShipment['customsClearanceDetail'] = [
                    'commercialInvoice' => [
                        'shipmentPurpose' => 'SOLD',
                    ],
                    'dutiesPayment' => [
                        'paymentType' => 'SENDER',
                        'payor' => [
                            'responsibleParty' => [
                                'address' => [
                                    'streetLines' => $splitStreet($shipperAddress),
                                    'city'        => $shipperCity,
                                    'postalCode'  => $shipperPostal,
                                    'countryCode' => $shipperCountry,
                                    'residential' => false,
                                ],
                                'accountNumber' => [
                                    'value' => $accountNumber,
                                ],
                            ],
                        ],
                    ],
                    'commodities' => $commodities,
                ];
            }

            $payload = [
                'accountNumber' => [
                    'value' => $accountNumber,
                ],
                'rateRequestControlParameters' => [
                    'returnTransitTimes'         => true,
                    'servicesNeededOnRateFailure' => true,
                ],
                'requestedShipment' => $requestedShipment,
            ];

            Log::info('FedexService->getRates: Payload built', [
                'payload' => $payload,
                'hasCustomsClearanceDetail' => array_key_exists('customsClearanceDetail', $requestedShipment),
            ]);

            // ---------------------------------------------------------
            // 4. Hit FedEx API
            // ---------------------------------------------------------
            $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(60)
                ->post("{$this->apiUrl}/rate/v1/rates/quotes", $payload);

            $this->logRequest('rate/v1/rates/quotes', $payload, $response);


            Log::info('FedexService->getRates: API response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            // ---------------------------------------------------------
            // 5. Handle error response
            // ---------------------------------------------------------
            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMsg  = $errorBody['errors'][0]['message']
                    ?? $errorBody['message']
                    ?? 'FedEx API request failed.';

                Log::error('FedexService->getRates: API call failed', [
                    'status' => $response->status(),
                    'error'  => $errorMsg,
                    'body'   => $response->body(),
                ]);

                throw new \Exception('FedEx API Error: ' . $errorMsg);
            }

            // ---------------------------------------------------------
            // 6. Cek error di body meski status 2xx
            // ---------------------------------------------------------
            $responseBody = $response->json();
            if (!empty($responseBody['errors'])) {
                $errorMsg = $responseBody['errors'][0]['message'] ?? 'Unknown FedEx error.';
                Log::warning('FedexService->getRates: FedEx returned errors in body', [
                    'errors' => $responseBody['errors'],
                ]);
                throw new \Exception('FedEx Error: ' . $errorMsg);
            }

            // ---------------------------------------------------------
            // 7. Ambil data rates dan FORMAT sebelum return
            // ---------------------------------------------------------
            $rateDetails = $response->json('output.rateReplyDetails');

            if (!is_array($rateDetails) || count($rateDetails) === 0) {
                Log::warning('FedexService->getRates: No rates returned from FedEx');
                return [];
            }

            $formatted = [];
            foreach ($rateDetails as $rate) {
                if (!isset($rate['ratedShipmentDetails'][0]['totalNetCharge'])) {
                    continue;
                }

                $commit   = $rate['commit'] ?? null;
                $delivery = 'N/A';

                if ($commit) {
                    if (isset($commit['deliveryTimestamp'])) {
                        $delivery = \Carbon\Carbon::parse($commit['deliveryTimestamp'])
                            ->format('d M Y, H:i');
                    } elseif (isset($commit['dateDetail']['dayOfWeek'])) {
                        $delivery = 'Estimated by ' . $commit['dateDetail']['dayOfWeek'];
                    }
                }

                $formatted[] = [
                    'service_name'       => $rate['serviceName']                              ?? 'Unknown Service',
                    'service_type'       => $rate['serviceType']                              ?? 'UNKNOWN',
                    'delivery_timestamp' => $delivery,
                    'total_charge'       => $rate['ratedShipmentDetails'][0]['totalNetCharge'],
                    'currency'           => $rate['ratedShipmentDetails'][0]['currency']      ?? 'USD',
                ];
            }

            Log::info('FedexService->getRates: Formatted rates', ['count' => count($formatted)]);

            return $formatted;
        } catch (\Throwable $e) {
            Log::error('FedexService->getRates: Exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            // Re-throw supaya CartController bisa handle
            throw $e;
        }
    }

    /**
     * Track Multiple Piece Shipment (MPS) — mengambil daftar tracking number
     * yang terasosiasi dengan sebuah master tracking number.
     *
     * Endpoint: POST /track/v1/associatedshipments
     *
     * Contoh pemakaian:
     *  $fedex->getAssociatedShipments([
     *      'trackingNumber'    => '794657194545',   // wajib
     *      'shipDateBegin'     => '2018-11-01',      // opsional
     *      'shipDateEnd'       => '2018-11-03',      // opsional
     *      'associatedType'    => 'STANDARD_MPS',    // opsional, default STANDARD_MPS
     *      'includeDetailedScans' => true,           // opsional, default true
     *      'resultsPerPage'    => 20,                // opsional
     *      'pagingToken'       => null,              // opsional, dipakai untuk halaman berikutnya
     *  ]);
     *
     * @param array $data
     * @return array
     */
    public function getAssociatedShipments(array $data): array
    {
        try {
            Log::info('FedexService->getAssociatedShipments: Start', ['data' => $data]);

            // ---------------------------------------------------------
            // 1. Validasi data masuk
            // ---------------------------------------------------------
            $trackingNumber = trim($data['trackingNumber'] ?? '');

            if (empty($trackingNumber)) {
                throw new \InvalidArgumentException('trackingNumber wajib diisi.');
            }

            $associatedType       = $data['associatedType'] ?? 'STANDARD_MPS';
            $includeDetailedScans = array_key_exists('includeDetailedScans', $data)
                ? (bool) $data['includeDetailedScans']
                : true;

            // ---------------------------------------------------------
            // 2. Bangun trackingNumberInfo
            // ---------------------------------------------------------
            $trackingNumberInfo = [
                'trackingNumber' => $trackingNumber,
            ];

            if (!empty($data['trackingNumberUniqueId'])) {
                $trackingNumberInfo['trackingNumberUniqueId'] = $data['trackingNumberUniqueId'];
            }

            if (!empty($data['carrierCode'])) {
                $trackingNumberInfo['carrierCode'] = $data['carrierCode'];
            }

            // ---------------------------------------------------------
            // 3. Bangun masterTrackingNumberInfo
            // ---------------------------------------------------------
            $masterTrackingNumberInfo = [
                'trackingNumberInfo' => $trackingNumberInfo,
            ];

            if (!empty($data['shipDateBegin'])) {
                $masterTrackingNumberInfo['shipDateBegin'] = $data['shipDateBegin'];
            }

            if (!empty($data['shipDateEnd'])) {
                $masterTrackingNumberInfo['shipDateEnd'] = $data['shipDateEnd'];
            }

            // ---------------------------------------------------------
            // 4. Bangun payload utama
            // ---------------------------------------------------------
            $payload = [
                'includeDetailedScans'    => $includeDetailedScans,
                'associatedType'          => $associatedType,
                'masterTrackingNumberInfo' => $masterTrackingNumberInfo,
            ];

            // ---------------------------------------------------------
            // 5. pagingDetails — opsional (dipakai untuk pagination)
            // ---------------------------------------------------------
            if (!empty($data['resultsPerPage']) || !empty($data['pagingToken'])) {
                $pagingDetails = [];

                if (!empty($data['resultsPerPage'])) {
                    $pagingDetails['resultsPerPage'] = (int) $data['resultsPerPage'];
                }

                if (!empty($data['pagingToken'])) {
                    $pagingDetails['pagingToken'] = $data['pagingToken'];
                }

                $payload['pagingDetails'] = $pagingDetails;
            }

            Log::info('FedexService->getAssociatedShipments: Payload built', ['payload' => $payload]);

            // ---------------------------------------------------------
            // 6. Ambil access token & hit FedEx API
            // ---------------------------------------------------------
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(60)
                ->post("{$this->apiUrl}/track/v1/associatedshipments", $payload);

            $this->logRequest('track/v1/associatedshipments', $payload, $response);

            Log::info('FedexService->getAssociatedShipments: API response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            // ---------------------------------------------------------
            // 7. Handle error response
            // ---------------------------------------------------------
            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMsg  = $errorBody['errors'][0]['message']
                    ?? $errorBody['message']
                    ?? 'FedEx API request failed.';

                Log::error('FedexService->getAssociatedShipments: API call failed', [
                    'status' => $response->status(),
                    'error'  => $errorMsg,
                    'body'   => $response->body(),
                ]);

                throw new \Exception('FedEx API Error: ' . $errorMsg);
            }

            $responseBody = $response->json();

            if (!empty($responseBody['errors'])) {
                $errorMsg = $responseBody['errors'][0]['message'] ?? 'Unknown FedEx error.';
                Log::warning('FedexService->getAssociatedShipments: FedEx returned errors in body', [
                    'errors' => $responseBody['errors'],
                ]);
                throw new \Exception('FedEx Error: ' . $errorMsg);
            }

            return $responseBody;
        } catch (\Throwable $e) {
            Log::error('FedexService->getAssociatedShipments: Exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            throw $e;
        }
    }

    public function uploadDocument(string $trackingNumber, string $pdfPath): array
    {
        $token = $this->getAccessToken();

        $documentPayload = [
            'referenceId' => $trackingNumber,
            'name'        => basename($pdfPath),
            'contentType' => 'application/pdf',
            'meta' => [
                'shipDocumentType'       => 'COMMERCIAL_INVOICE',
                'trackingNumber'         => $trackingNumber,
                'shipmentDate'           => now()->format('Y-m-d'),
                'carrierCode'            => 'FDXE',
                'originCountryCode'      => 'ID',
                'destinationCountryCode' => 'US', // hardcode dulu atau ambil dari parameter
            ],
        ];

        $rulesPayload = [
            'workflowName' => 'ETDPostShipment',
        ];

        $response = Http::withToken($token)
            ->asMultipart()
            ->timeout(60)
            ->post("{$this->apiUrl}/documents/v1/etds/upload", [
                [
                    'name'     => 'document',
                    'contents' => json_encode($documentPayload),
                    'headers'  => ['Content-Type' => 'application/json'],
                ],
                [
                    'name'     => 'rules',
                    'contents' => json_encode($rulesPayload),
                    'headers'  => ['Content-Type' => 'application/json'],
                ],
                [
                    'name'     => 'attachment',
                    'contents' => fopen($pdfPath, 'r'),
                    'filename' => basename($pdfPath),
                    'headers'  => ['Content-Type' => 'application/pdf'],
                ],
            ]);

        if ($response->failed()) {
            throw new \Exception("FedEx Upload Document API error: " . $response->body());
        }

        return $response->json();
    }
}
