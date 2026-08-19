<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FedexLog;
use App\Models\FedexToken;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedExController extends Controller
{
    public $mode;
    public $api_url;
    public $client_id;
    public $client_secret;
    public $account_number;
    public $shipper;

    public function __construct()
    {
        $this->mode = config('services.fedex.mode');
        $this->api_url = $this->mode === 'sandbox' ? config('services.fedex.sandbox_url') : ''; // Add production URL
        $this->client_id = config('services.fedex.client_id');
        $this->client_secret = config('services.fedex.client_secret');
        $this->account_number = config('services.fedex.account_number');
        $this->shipper = config('services.fedex.shipper');
    }

    // ============================================================
    // GET ACCESS TOKEN FROM FEDEX
    // ============================================================
    private function getAccessToken()
    {
        try {
            $token = FedexToken::first();

            // Check if token exists and is not expired (with a 5-minute buffer)
            if (!$token || now()->gte($token->updated_at->addSeconds((int) $token->expires_in - 300))) {
                $response = Http::asForm()->post($this->api_url . '/oauth/token', [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    $tokenData = [
                        'access_token' => $data['access_token'],
                        'token_type' => $data['token_type'],
                        'expires_in' => $data['expires_in'],
                    ];

                    FedexToken::updateOrCreate(['id' => 1], $tokenData);

                    return $data['access_token'];
                } else {
                    Log::error('FedEx token refresh failed', ['response' => $response->body()]);
                    throw new \Exception('Failed to retrieve access token from FedEx.');
                }
            }

            return $token->access_token;
        } catch (\Exception $e) {
            Log::error('Error in getAccessToken: ' . $e->getMessage());
            throw $e;
        }
    }

    // ============================================================
    // HELPER: Bangun objek dutiesPayment / responsibleParty yang lengkap
    // (dipakai bersama di getRates & createShipment agar konsisten)
    // ============================================================
    private function buildResponsiblePartyPayor(): array
    {
        return [
            'responsibleParty' => [
                'address' => [
                    'streetLines' => $this->splitStreetLines($this->shipper['address']),
                    'city'        => $this->shipper['city'],
                    'postalCode'  => $this->shipper['postal_code'],
                    'countryCode' => $this->shipper['country_code'],
                    'residential' => false,
                ],
                'accountNumber' => [
                    'value' => $this->account_number,
                ],
            ],
        ];
    }

    // ============================================================
    // HELPER: Bangun array commodities dari list item generik
    // Dipakai khusus oleh getRates() karena request-nya cuma per-shipment,
    // bukan per-produk seperti di createShipment()
    // ============================================================
    private function buildCommoditiesFromItems(array $items, float $totalWeight, float $usdTotalValue): array
    {
        $commodities = [];

        if (!empty($items)) {
            foreach ($items as $item) {
                $qty = (int) ($item['qty'] ?? 1);
                $weightPerUnit = (float) ($item['gros'] ?? 0);
                $unitPrice = (float) ($item['price'] ?? ($usdTotalValue / max(count($items), 1)));

                $commodities[] = [
                    'description'          => trim($item['description'] ?? $item['name'] ?? 'General Merchandise'),
                    'name'                  => trim($item['name'] ?? 'General Merchandise'),
                    'countryOfManufacture'  => $item['countryOfManufacture'] ?? 'ID',
                    'quantity'              => $qty,
                    'quantityUnits'         => 'PCS',
                    'numberOfPieces'        => $qty,
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
                        'value' => $weightPerUnit * $qty,
                    ],
                ];
            }
        }

        // Fallback: kalau items kosong / tidak lengkap, buat satu commodity umum
        if (empty($commodities)) {
            $commodities[] = [
                'description'          => 'General Merchandise',
                'name'                  => 'General Merchandise',
                'countryOfManufacture'  => 'ID',
                'quantity'              => 1,
                'quantityUnits'         => 'PCS',
                'numberOfPieces'        => 1,
                'unitPrice' => [
                    'amount'   => round($usdTotalValue, 2),
                    'currency' => 'USD',
                ],
                'customsValue' => [
                    'amount'   => round($usdTotalValue, 2),
                    'currency' => 'USD',
                ],
                'weight' => [
                    'units' => 'KG',
                    'value' => $totalWeight,
                ],
            ];
        }

        return $commodities;
    }

    // ============================================================
    // GET SHIPPING RATES
    // ============================================================
    public function getRates(Request $request)
    {
        Log::info('FedEx Config Check', [
            'account_number' => $this->account_number,
            'shipper' => $this->shipper,
            'api_url' => $this->api_url,
            'mode' => $this->mode,
        ]);

        try {
            Log::info('FedEx getRates method called.');
            Log::info('Request data:', $request->all());

            $validated = $request->validate([
                'destinationZip' => 'required|string|max:10',
                'totalWeight' => 'required|numeric',
                'destinationCountry' => 'required|string|size:2',
                'destinationCity' => 'required|string|max:100',
                'state' => 'nullable|string|max:100',
                'destinationStreet' => 'required|string|max:255',
                'totalValue' => 'required|numeric',
                'items' => 'required|array',
            ]);

            $usdTotalValue = (float) $validated['totalValue'];

            $packageLineItems = [];
            foreach ($validated['items'] as $item) {
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

            if (empty($packageLineItems)) {
                $packageLineItems[] = [
                    'weight' => [
                        'units' => 'KG',
                        'value' => $validated['totalWeight'],
                    ],
                ];
            }

            $token = $this->getAccessToken();
            if (!$token) {
                return response()->json(['error' => 'Failed to authenticate with FedEx.'], 500);
            }

            $url = $this->api_url . '/rate/v1/rates/quotes';

            $recipientAddress = [
                'streetLines' => $this->splitStreetLines($validated['destinationStreet']),
                'city'        => $validated['destinationCity'],
                'postalCode'  => $validated['destinationZip'],
                'countryCode' => $validated['destinationCountry'],
            ];
            if (!empty($validated['state'])) {
                $recipientAddress['stateOrProvinceCode'] = $validated['state'];
            }

            // --- Deteksi apakah shipment ini domestik atau internasional ---
            $isInternational = strtoupper($validated['destinationCountry']) !== strtoupper($this->shipper['country_code']);

            $requestedShipment = [
                'shipDateStamp' => now()->format('Y-m-d'),
                'shipper' => [
                    // NOTE: accountNumber TIDAK termasuk field valid di dalam objek
                    // requestedShipment.shipper pada schema FedEx Rate API.
                    // Account number cukup dikirim di root payload & di
                    // shippingChargesPayment/dutiesPayment. Field ini dihapus
                    // untuk menghindari kemungkinan ditolak oleh strict validation.
                    'address' => [
                        'streetLines' => $this->splitStreetLines($this->shipper['address']),
                        'city'        => $this->shipper['city'],
                        'postalCode'  => $this->shipper['postal_code'],
                        'countryCode' => $this->shipper['country_code'],
                    ],
                ],
                'recipient' => [
                    'address' => $recipientAddress,
                ],
                'pickupType'      => 'DROPOFF_AT_FEDEX_LOCATION',
                'rateRequestType' => ['ACCOUNT', 'LIST'],
                'packagingType'   => 'YOUR_PACKAGING',
                'requestedPackageLineItems' => $packageLineItems,
                'shippingChargesPayment' => [
                    'paymentType' => 'SENDER',
                    'payor' => $this->buildResponsiblePartyPayor(),
                ],
            ];

            // customsClearanceDetail HANYA disertakan untuk shipment internasional
            if ($isInternational) {
                $requestedShipment['customsClearanceDetail'] = [
                    'commercialInvoice' => [
                        'shipmentPurpose' => 'SOLD',
                    ],
                    'dutiesPayment' => [
                        'paymentType' => 'SENDER',
                        'payor' => $this->buildResponsiblePartyPayor(),
                    ],
                    'commodities' => $this->buildCommoditiesFromItems(
                        $validated['items'],
                        (float) $validated['totalWeight'],
                        $usdTotalValue
                    ),
                ];
            }

            $payload = [
                'accountNumber' => ['value' => $this->account_number],
                'rateRequestControlParameters' => [
                    'returnTransitTimes' => true,
                    'servicesNeededOnRateFailure' => true,
                ],
                'requestedShipment' => $requestedShipment,
            ];

            Log::info('FedEx getRates payload:', ['payload' => $payload]);

            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            Log::info('FedEx response:', ['response' => $response->json()]);

            FedexLog::create([
                'endpoint' => $url,
                'request_json' => json_encode($payload),
                'response_json' => $response->body(),
                'status_code' => $response->status(),
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Failed to get rates from FedEx.',
                    'details' => $response->json(),
                ], $response->status());
            }

            $responseBody = $response->json();
            if (isset($responseBody['errors'])) {
                Log::warning('FedEx API returned errors.', ['errors' => $responseBody['errors']]);
                return response()->json([
                    'error' => 'FedEx service is currently unavailable.',
                    'details' => $responseBody['errors'][0]['message'] ?? 'No additional details provided.',
                ], 503);
            }

            $rates = [];
            $rateDetails = $response->json('output.rateReplyDetails');

            if (is_array($rateDetails)) {
                foreach ($rateDetails as $rate) {
                    if (isset($rate['ratedShipmentDetails'][0]['totalNetCharge'])) {
                        $commit = $rate['commit'] ?? null;

                        Log::info('FedEx Rate Service', [
                            'serviceType' => $rate['serviceType'] ?? null,
                            'serviceName' => $rate['serviceName'] ?? null,
                            'hasCommit'   => !is_null($commit),
                            'commit'      => $commit,
                        ]);

                        $delivery = 'N/A';
                        if ($commit) {
                            if (isset($commit['deliveryTimestamp'])) {
                                $delivery = \Carbon\Carbon::parse($commit['deliveryTimestamp'])->format('d M Y, H:i');
                            } elseif (isset($commit['dateDetail']['dayOfWeek'])) {
                                $delivery = 'Estimated by ' . $commit['dateDetail']['dayOfWeek'];
                            }
                        }

                        $rates[] = [
                            'service_name' => $rate['serviceName'],
                            'service_type' => $rate['serviceType'],
                            'delivery_timestamp' => $delivery,
                            'total_charge' => $rate['ratedShipmentDetails'][0]['totalNetCharge'],
                            'currency' => $rate['ratedShipmentDetails'][0]['currency'],
                        ];

                        ShippingRate::create([
                            'idexpedisi' => 1, // FedEx
                            'carrier' => 'FedEx',
                            'service_type' => $rate['serviceType'],
                            'origin' => $this->shipper['postal_code'],
                            'destination' => $validated['destinationZip'],
                            'weight' => $validated['totalWeight'],
                            'price' => $rate['ratedShipmentDetails'][0]['totalNetCharge'],
                            'currency' => $rate['ratedShipmentDetails'][0]['currency'],
                            'etd' => $delivery,
                            'response_json' => json_encode($rate),
                        ]);
                    }
                }
            }

            if (empty($rates)) {
                Log::warning('No FedEx rates found for the given details.');
                return response()->json([
                    'success' => true,
                    'rates' => [],
                ]);
            }

            Log::info('FedEx rates found:', ['rates' => $rates]);

            return response()->json([
                'success' => true,
                'rates' => $rates,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getRates: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.', 'details' => $e->getMessage()], 500);
        }
    }

    // ============================================================
    // CREATE FEDEX SHIPMENT
    // ============================================================
    public function createShipment($id)
    {
        $invoice = \App\Models\TransaksiInvoice::with([
            'transaksi.user.userDetail.negara',
            'transaksi.address',
            'transaksi.details.produk',
        ])->where('idtrans', $id)->firstOrFail();

        $address = $invoice->transaksi->address;
        if (!$address) {
            return response()->json(['error' => 'Alamat pengiriman tidak ditemukan untuk transaksi ini.'], 404);
        }

        $total_weight = $invoice->transaksi->details->sum(function ($detail) {
            return $detail->gros * $detail->qty;
        }) + 0.5;

        $isInternational = strtoupper($address->kode_iso) !== strtoupper($this->shipper['country_code']);

        $commodities = [];
        foreach ($invoice->transaksi->details as $detail) {
            $qty = (int) $detail->qty;
            $unitPrice = (float) $detail->harga;

            $commodities[] = [
                'description'          => trim($detail->produk->nama) ?: 'Goods as per commercial invoice',
                'name'                  => trim($detail->produk->nama) ?: 'Goods as per commercial invoice',
                'countryOfManufacture'  => 'ID',
                'quantity'              => $qty,
                'quantityUnits'         => 'EA',
                'numberOfPieces'        => $qty,
                'unitPrice' => [
                    'amount'   => $unitPrice,
                    'currency' => $invoice->transaksi->shipping_currency ?? 'USD',
                ],
                'customsValue' => [
                    'amount'   => $unitPrice * $qty,
                    'currency' => 'USD',
                ],
                'weight' => [
                    'units' => 'KG',
                    'value' => $detail->gros * $qty,
                ],
            ];
        }

        $token = $this->getAccessToken();
        if (!$token) {
            return response()->json(['error' => 'Failed to authenticate with FedEx.'], 500);
        }

        $url = $this->api_url . '/ship/v1/shipments';

        $shipperAddress = [
            'streetLines' => $this->splitStreetLines($this->shipper['address']),
            'city' => $this->shipper['city'],
            'postalCode' => $this->shipper['postal_code'],
            'countryCode' => $this->shipper['country_code'],
        ];
        $shipperContact = [
            'personName' => $this->shipper['name'],
            'phoneNumber' => substr(preg_replace('/[^0-9]/', '', $this->shipper['phone']), 0, 15),
            'companyName' => $this->shipper['company'],
        ];

        $recipientAddress = [
            'streetLines' => $this->splitStreetLines($address->alamat),
            'city' => $address->city,
            'postalCode' => $address->zip_code,
            'countryCode' => $address->kode_iso,
        ];

        if (!empty($address->state)) {
            $recipientAddress['stateOrProvinceCode'] = $address->state;
        }

        $recipients = [
            [
                'contact' => [
                    'personName' => $address->nama,
                    'phoneNumber' => $address->phone,
                    'companyName' => $address->nama,
                ],
                'address' => $recipientAddress,
            ],
        ];

        $requestedShipment = [
            'shipper' => [
                'contact' => $shipperContact,
                'address' => $shipperAddress,
            ],
            'recipients' => $recipients,
            'shipDatestamp' => now()->addDay()->format('Y-m-d'),
            'serviceType' => $invoice->transaksi->shipping_service,
            'packagingType' => 'YOUR_PACKAGING',
            'pickupType' => 'DROPOFF_AT_FEDEX_LOCATION',
            'blockInsightVisibility' => false,
            'shippingChargesPayment' => [
                'paymentType' => 'SENDER',
                'payor' => $this->buildResponsiblePartyPayor(),
            ],
            'labelSpecification' => [
                'imageType' => 'PDF',
                'labelStockType' => 'PAPER_4X6',
            ],
            'requestedPackageLineItems' => [
                [
                    'weight' => [
                        'units' => 'KG',
                        'value' => $total_weight,
                    ],
                ],
            ],
        ];

        // customsClearanceDetail HANYA disertakan untuk pengiriman internasional
        if ($isInternational) {
            $requestedShipment['customsClearanceDetail'] = [
                'commercialInvoice' => [
                    'shipmentPurpose' => 'SOLD',
                ],
                'commodities' => $commodities,
                'dutiesPayment' => [
                    'paymentType' => 'SENDER',
                    'payor' => $this->buildResponsiblePartyPayor(),
                ],
            ];
        }

        $payload = [
            'labelResponseOptions' => 'URL_ONLY',
            'requestedShipment' => $requestedShipment,
            'accountNumber' => [
                'value' => $this->account_number,
            ],
        ];

        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $payload);

        FedexLog::create([
            'endpoint' => $url,
            'request_json' => json_encode($payload),
            'response_json' => $response->body(),
            'status_code' => $response->status(),
        ]);

        if ($response->failed()) {
            $shipmentDetails = [
                'idtrans' => $id,
                'service_type' => $invoice->transaksi->shipping_service,
                'rate_response' => $response->body(),
                'shipper_address' => json_encode($shipperAddress),
                'recipient_address' => json_encode($recipientAddress),
                'weight' => $total_weight,
                'status' => 'failed',
                'shipment_id' => null,
                'tracking_number' => null,
                'label_url' => null,
                'total_charge' => 0,
                'currency' => $invoice->transaksi->shipping_currency ?? 'USD',
            ];
            \App\Models\FedexShipment::create($shipmentDetails);

            return response()->json([
                'error' => 'Failed to create shipment with FedEx.',
                'details' => $response->json(),
            ], $response->status());
        }

        $responseData = $response->json();
        $output = $responseData['output'] ?? [];
        $transactionShipments = $output['transactionShipments'][0] ?? [];

        $shipmentDetails = [
            'idtrans' => $id,
            'shipment_id' => $transactionShipments['masterTrackingNumber'] ?? null,
            'tracking_number' => $transactionShipments['masterTrackingNumber'] ?? null,
            'label_url' => $transactionShipments['pieceResponses'][0]['packageDocuments'][0]['url'] ?? null,
            'service_type' => $invoice->transaksi->shipping_service,
            'total_charge' => $transactionShipments['shipmentRateDetails'][0]['totalNetCharge'] ?? 0,
            'currency' => $transactionShipments['shipmentRateDetails'][0]['currency'] ?? 'USD',
            'rate_response' => json_encode($transactionShipments),
            'shipper_address' => json_encode($shipperAddress),
            'recipient_address' => json_encode($recipientAddress),
            'weight' => $total_weight,
            'status' => 'success',
        ];

        \App\Models\FedexShipment::create($shipmentDetails);

        // Update Transaksi status and create TransaksiProses
        $transaksi = $invoice->transaksi;
        $transaksi->status = 3; // Update status to 'shipped'
        $transaksi->save();

        $invoice->status = 3;
        $invoice->save();

        // Generate new kode_ship
        $lastProses = \App\Models\TransaksiProses::orderBy('id', 'desc')->first();
        $newKodeShip = 'ship-001';
        if ($lastProses && $lastProses->kode_ship) {
            $lastKodeShipNumber = (int) substr($lastProses->kode_ship, 5);
            $newKodeShip = 'ship-' . str_pad($lastKodeShipNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        \App\Models\TransaksiProses::create([
            'idtrans' => $transaksi->id,
            'kode_ship' => $newKodeShip,
            'status' => 3, // Shipped
            'kode_inv' => $invoice->kode_inv,
            'no_resi' => $shipmentDetails['tracking_number'],
            'expedisi' => 'FedEx',
        ]);

        // Kurangi stok produk
        try {
            if ($invoice->transaksi->details->isNotEmpty()) {
                foreach ($invoice->transaksi->details as $detail) {
                    $stokProduk = \App\Models\ProdukStok::where('id_produk', $detail->idproduk)
                        ->where('id_jenis', $detail->id_jenis)
                        ->where('id_ukuran', $detail->id_ukuran)
                        ->first();

                    if ($stokProduk) {
                        $stokProduk->stok -= $detail->qty;
                        $stokProduk->save();
                    } else {
                        Log::warning('Product stock variant not found, stock not updated.', [
                            'invoice_id' => $invoice->id,
                            'transaction_id' => $invoice->idtrans,
                            'product_id' => $detail->idproduk,
                            'id_jenis' => $detail->id_jenis,
                            'id_ukuran' => $detail->id_ukuran,
                        ]);
                    }
                }
            } else {
                Log::warning('No details found for transaction, stock not updated.', [
                    'invoice_id' => $invoice->id,
                    'transaction_id' => $invoice->idtrans,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating stock: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'transaction_id' => $invoice->idtrans,
            ]);
        }

        return response()->json([
            'success' => true,
            'shipment' => $shipmentDetails,
        ]);
    }

    // ============================================================
    // TRACK MULTIPLE PIECE SHIPMENT (MPS) — ASSOCIATED SHIPMENTS
    // POST /track/v1/associatedshipments
    // ============================================================
    public function associatedShipments(Request $request)
    {
        Log::info('FedEx associatedShipments method called.');

        try {
            $validated = $request->validate([
                'trackingNumber'         => 'required|string',
                'trackingNumberUniqueId' => 'nullable|string',
                'carrierCode'            => 'nullable|string',
                'shipDateBegin'          => 'nullable|date_format:Y-m-d',
                'shipDateEnd'            => 'nullable|date_format:Y-m-d',
                'associatedType'         => 'nullable|string|in:STANDARD_MPS',
                'includeDetailedScans'   => 'nullable|boolean',
                'resultsPerPage'         => 'nullable|integer|min:1',
                'pagingToken'            => 'nullable|string',
            ]);

            Log::info('FedEx associatedShipments request:', $validated);

            $token = $this->getAccessToken();
            if (!$token) {
                Log::error('FedEx associatedShipments: Failed to get access token.');
                return response()->json(['error' => 'Failed to authenticate with FedEx.'], 500);
            }

            $url = $this->api_url . '/track/v1/associatedshipments';

            // --- Bangun trackingNumberInfo ---
            $trackingNumberInfo = [
                'trackingNumber' => $validated['trackingNumber'],
            ];
            if (!empty($validated['trackingNumberUniqueId'])) {
                $trackingNumberInfo['trackingNumberUniqueId'] = $validated['trackingNumberUniqueId'];
            }
            if (!empty($validated['carrierCode'])) {
                $trackingNumberInfo['carrierCode'] = $validated['carrierCode'];
            }

            // --- Bangun masterTrackingNumberInfo ---
            $masterTrackingNumberInfo = [
                'trackingNumberInfo' => $trackingNumberInfo,
            ];
            if (!empty($validated['shipDateBegin'])) {
                $masterTrackingNumberInfo['shipDateBegin'] = $validated['shipDateBegin'];
            }
            if (!empty($validated['shipDateEnd'])) {
                $masterTrackingNumberInfo['shipDateEnd'] = $validated['shipDateEnd'];
            }

            // --- Payload utama ---
            $payload = [
                'includeDetailedScans'     => array_key_exists('includeDetailedScans', $validated)
                    ? (bool) $validated['includeDetailedScans']
                    : true,
                'associatedType'           => $validated['associatedType'] ?? 'STANDARD_MPS',
                'masterTrackingNumberInfo' => $masterTrackingNumberInfo,
            ];

            // --- pagingDetails opsional (untuk pagination hasil) ---
            if (!empty($validated['resultsPerPage']) || !empty($validated['pagingToken'])) {
                $pagingDetails = [];
                if (!empty($validated['resultsPerPage'])) {
                    $pagingDetails['resultsPerPage'] = (int) $validated['resultsPerPage'];
                }
                if (!empty($validated['pagingToken'])) {
                    $pagingDetails['pagingToken'] = $validated['pagingToken'];
                }
                $payload['pagingDetails'] = $pagingDetails;
            }

            Log::info('FedEx associatedShipments payload:', ['payload' => $payload]);

            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            FedexLog::create([
                'endpoint' => $url,
                'request_json' => json_encode($payload),
                'response_json' => $response->body(),
                'status_code' => $response->status(),
            ]);

            Log::info('FedEx associatedShipments response:', ['response' => $response->json()]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Failed to get associated shipments from FedEx.',
                    'details' => $response->json(),
                ], $response->status());
            }

            $responseBody = $response->json();

            if (!empty($responseBody['errors'])) {
                Log::warning('FedEx API returned errors.', ['errors' => $responseBody['errors']]);
                return response()->json([
                    'error' => 'FedEx service is currently unavailable.',
                    'details' => $responseBody['errors'][0]['message'] ?? 'No additional details provided.',
                ], 503);
            }

            return response()->json([
                'success' => true,
                'data' => $responseBody['output'] ?? $responseBody,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error in associatedShipments: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.', 'details' => $e->getMessage()], 500);
        }
    }

    // ============================================================
    // VALIDATE FEDEX ADDRESS
    // ============================================================
    public function validateAddress(Request $request)
    {
        Log::info('FedEx validateAddress method called.');
        try {
            $validated = $request->validate([
                'address1' => 'required|string',
                'address2' => 'nullable|string',
                'city' => 'required|string',
                'stateOrProvinceCode' => 'nullable|string',
                'postalCode' => 'required|string',
                'countryCode' => 'required|string',
            ]);
            Log::info('FedEx address validation request:', $validated);

            $token = $this->getAccessToken();
            if (!$token) {
                Log::error('FedEx address validation: Failed to get access token.');
                return response()->json(['error' => 'Failed to authenticate with FedEx.'], 500);
            }

            $url = $this->api_url . '/address/v1/addresses/resolve';

            $payload = [
                'inEffectAsOfTimestamp' => now()->format('Y-m-d'),
                'validateAddressControlParameters' => [
                    'includeResolutionTokens' => true,
                ],
                'addressesToValidate' => [
                    [
                        'address' => [
                            'streetLines' => $validated['address1'] ? [$validated['address1']] : [],
                            'city' => $validated['city'],
                            'stateOrProvinceCode' => $validated['stateOrProvinceCode'],
                            'postalCode' => $validated['postalCode'],
                            'countryCode' => $validated['countryCode'],
                        ],
                        'clientReferenceId' => 'AddressValidation-1',
                    ],
                ],
            ];
            Log::info('FedEx address validation payload:', $payload);

            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            FedexLog::create([
                'endpoint' => $url,
                'request_json' => json_encode($payload),
                'response_json' => $response->body(),
                'status_code' => $response->status(),
            ]);

            if ($response->failed()) {
                Log::error('FedEx address validation failed.', ['response' => $response->body()]);
                return response()->json([
                    'error' => 'Failed to validate address with FedEx.',
                    'details' => $response->json(),
                ], $response->status());
            }

            Log::info('FedEx address validation successful.', ['response' => $response->json()]);
            return response()->json($response->json());
        } catch (\Exception $e) {
            Log::error('Error in validateAddress: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate streetLines sesuai aturan spesifik negara (mis. Thailand harus 1 baris).
     */
    private function generateFedExStreetLines(string $address1, ?string $address2, string $countryCode): array
    {
        $clean = function (?string $text) {
            if (!$text) return null;
            $text = str_replace(["\n", "\r", "\t"], ' ', $text);
            $text = preg_replace('/\s+/', ' ', $text);
            return trim($text);
        };

        $address1 = $clean($address1);
        $address2 = $clean($address2);

        if (strtoupper($countryCode) === 'TH') {
            return [trim($address1 . ' ' . $address2)];
        }

        $streetLines = array_filter([$address1, $address2]);
        return array_slice($streetLines, 0, 2);
    }

    private function splitStreetLines($addressLine1, $addressLine2 = null)
    {
        $fullAddress = trim($addressLine1 . ' ' . ($addressLine2 ?? ''));
        $wrappedAddress = wordwrap($fullAddress, 35, "\n", true);
        $lines = explode("\n", $wrappedAddress);
        return array_slice($lines, 0, 2);
    }
}
