<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FedexLog;
use App\Models\FedexToken;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
// Impor FedExController

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
            if (!$token || now()->gte($token->updated_at->addSeconds($token->expires_in - 300))) {
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

                    // Use updateOrCreate to manage the single token record.
                    // This will update the first record or create it if it doesn't exist.
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
            throw $e; // Re-throw the exception to be caught by the calling method
        }
    }

    // ============================================================
    // GET SHIPPING RATES
    // ============================================================
    public function getRates(Request $request)
    {
        try {
            Log::info('FedEx getRates method called.'); // Tambahkan log di sini

            // Validasi input
            $validated = $request->validate([
                'destinationZip' => 'required|string|max:10',
                'totalWeight' => 'required|numeric',
                'destinationCountry' => 'required|string|size:2',
                'state' => 'nullable|string|max:100'
            ]);

            $token = $this->getAccessToken();
            if (!$token) {
                return response()->json(['error' => 'Failed to authenticate with FedEx.'], 500);
            }

            $url = $this->api_url . '/rate/v1/rates/quotes';

            $recipientAddress = [
                "postalCode" => $validated['destinationZip'],
                "countryCode" => $validated['destinationCountry']
            ];
            if (!empty($validated['state'])) {
                $recipientAddress["stateOrProvinceCode"] = $validated['state'];
            }

            $payload = [
                "accountNumber" => ["value" => $this->account_number],
                "requestedShipment" => [
                    "shipDateStamp" => now()->format('Y-m-d'),
                    "returnTransitTimes" => true,
                    "shipper" => [
                        "address" => [
                            "postalCode" => $this->shipper['postal_code'],
                            "countryCode" => $this->shipper['country_code']
                        ]
                    ],
                    "recipient" => ["address" => $recipientAddress],
                    "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
                    "rateRequestType" => ["ACCOUNT"],
                    "requestedPackageLineItems" => [
                        [
                            "weight" => [
                                "units" => "KG",
                                "value" => $validated['totalWeight']
                            ]
                        ]
                    ]
                ]
            ];

            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            // Log ke fedex_log
            FedexLog::create([
                'endpoint' => $url,
                'request_json' => json_encode($payload),
                'response_json' => $response->body(),
                'status_code' => $response->status(),
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Failed to get rates from FedEx.',
                    'details' => $response->json()
                ], $response->status());
            }

            $rates = [];
            $rateDetails = $response->json('output.rateReplyDetails');

            if (is_array($rateDetails)) {
                foreach ($rateDetails as $rate) {
                    if (isset($rate['ratedShipmentDetails'][0]['totalNetCharge'])) {

                        // === TAMBAHKAN DI SINI ===
                        $commit = $rate['commit'] ?? null;

                        $delivery = $commit['deliveryTimestamp']
                            ?? ($commit['dateDetail']['dayOfWeek'] ?? null)
                            ?? 'N/A';
                        // =========================

                        $rates[] = [
                            'service_name' => $rate['serviceName'],
                            'service_type' => $rate['serviceType'],
                            'delivery_timestamp' => $delivery,
                            'total_charge' => $rate['ratedShipmentDetails'][0]['totalNetCharge'],
                            'currency' => $rate['ratedShipmentDetails'][0]['currency'],
                        ];

                        // Simpan ke shipping_rate
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

            Log::info('FedEx rates found:', ['rates' => $rates]); // Tambahkan log di sini

            return response()->json([
                'success' => true,
                'rates' => $rates
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
            'transaksi.details.produk'
        ])->where('idtrans', $id)->firstOrFail();

        $address = $invoice->transaksi->address;
        if (!$address) {
            return response()->json(['error' => 'Alamat pengiriman tidak ditemukan untuk transaksi ini.'], 404);
        }

        $total_weight = $invoice->transaksi->details->sum(function ($detail) {
            return $detail->gros * $detail->qty;
        }) + 0.5;

        $commodities = [];
        foreach ($invoice->transaksi->details as $detail) {
            $commodities[] = [
                'description' => trim($detail->produk->nama) ?: 'Goods as per commercial invoice',
                'countryOfManufacture' => 'ID',
                'quantity' => $detail->qty,
                'quantityUnits' => 'EA',
                'unitPrice' => [
                    'amount' => $detail->harga,
                    'currency' => $invoice->transaksi->shipping_currency ?? 'USD',
                ],
                'customsValue' => [
                    'amount' => $detail->harga * $detail->qty,
                    'currency' => $invoice->transaksi->shipping_currency ?? 'USD',
                ],
                'weight' => [
                    'units' => 'KG',
                    'value' => $detail->gros * $detail->qty,
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
            'stateOrProvinceCode' => $this->shipper['state'],
            'postalCode' => $this->shipper['postal_code'],
            'countryCode' => $this->shipper['country_code']
        ];
        $shipperContact = [
            'personName' => $this->shipper['name'],
            'phoneNumber' => substr(preg_replace('/[^0-9]/', '', $this->shipper['phone']), 0, 15),
            'companyName' => $this->shipper['company']
        ];

        $recipientAddress = [
            'streetLines' => $this->splitStreetLines($address->alamat),
            'city' => $address->city,
            'stateOrProvinceCode' => $address->state ?? '',
            'postalCode' => $address->zip_code,
            'countryCode' => $address->kode_iso,
        ];

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

        $customsClearanceDetail = [
            'commodities' => $commodities,
            'dutiesPayment' => [
                'paymentType' => 'SENDER',
                'payor' => [
                    'responsibleParty' => [
                        'accountNumber' => [
                            'value' => $this->account_number
                        ]
                    ]
                ]
            ]
        ];

        $payload = [
            'labelResponseOptions' => 'URL_ONLY',
            'requestedShipment' => [
                'shipper' => [
                    'contact' => $shipperContact,
                    'address' => $shipperAddress,
                ],
                'recipients' => $recipients,
                'shipDatestamp' => now()->format('Y-m-d'),
                'serviceType' => $invoice->transaksi->shipping_service,
                'packagingType' => 'YOUR_PACKAGING',
                'pickupType' => 'DROPOFF_AT_FEDEX_LOCATION',
                'blockInsightVisibility' => false,
                'shippingChargesPayment' => [
                    'paymentType' => 'SENDER',
                    'payor' => [
                        'responsibleParty' => [
                            'accountNumber' => [
                                'value' => $this->account_number
                            ]
                        ]
                    ]
                ],
                'customsClearanceDetail' => $customsClearanceDetail,
                'labelSpecification' => [
                    'imageType' => 'PDF',
                    'labelStockType' => 'PAPER_4X6',
                ],
                'requestedPackageLineItems' => [
                    [
                        'weight' => [
                            'units' => 'KG',
                            'value' => $total_weight
                        ]
                    ]
                ]
            ],
            'accountNumber' => [
                'value' => $this->account_number
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
                'details' => $response->json()
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
                        // Log jika stok untuk varian produk tidak ditemukan
                        \Illuminate\Support\Facades\Log::warning('Product stock variant not found, stock not updated.', [
                            'invoice_id' => $invoice->id,
                            'transaction_id' => $invoice->idtrans,
                            'product_id' => $detail->idproduk,
                            'id_jenis' => $detail->id_jenis,
                            'id_ukuran' => $detail->id_ukuran,
                        ]);
                    }
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('No details found for transaction, stock not updated.', [
                    'invoice_id' => $invoice->id,
                    'transaction_id' => $invoice->idtrans,
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating stock: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'transaction_id' => $invoice->idtrans,
            ]);
        }

        return response()->json([
            'success' => true,
            'shipment' => $shipmentDetails
        ]);
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

            $streetLines = $this->generateFedExStreetLines(
                $validated['address1'],
                $validated['address2'] ?? null,
                $validated['countryCode']
            );

            $address = [
                'streetLines' => $streetLines,
                'city' => $validated['city'],
                'postalCode' => $validated['postalCode'],
                'countryCode' => $validated['countryCode'],
            ];

            // Only add stateOrProvinceCode for specific countries that require it.
            if (in_array($validated['countryCode'], ['US', 'CA', 'IT', 'DE', 'GB']) && !empty($validated['stateOrProvinceCode'])) {
                $address['stateOrProvinceCode'] = $validated['stateOrProvinceCode'];
            }

            $payload = [
                'inEffectAsOfTimestamp' => now()->format('Y-m-d'),
                'validateAddressControlParameters' => [
                    'includeResolutionTokens' => true
                ],
                'addressesToValidate' => [
                    [
                        'address' => $address
                    ]
                ]
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
                    'details' => $response->json()
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
     * Generate the streetLines field for the FedEx API request based on country-specific rules.
     *
     * @param string $address1 The first line of the street address.
     * @param string|null $address2 The second line of the street address (optional).
     * @param string $countryCode The two-letter ISO country code.
     * @return array The formatted streetLines array for the FedEx API.
     */
    private function generateFedExStreetLines(string $address1, ?string $address2, string $countryCode): array
    {
        // Clean function: remove line breaks and extra spaces
        $clean = function (?string $text) {
            if (!$text) return null;
            $text = str_replace(["\n", "\r", "\t"], ' ', $text);
            $text = preg_replace('/\s+/', ' ', $text); // normalize spaces
            return trim($text);
        };

        $address1 = $clean($address1);
        $address2 = $clean($address2);

        // Thailand must be one line
        if (strtoupper($countryCode) === 'TH') {
            return [trim($address1 . ' ' . $address2)];
        }

        // Other countries: up to 2 lines
        $streetLines = array_filter([$address1, $address2]);
        return array_slice($streetLines, 0, 2); // FedEx max 2 lines
    }

    private function splitStreetLines($addressLine1, $addressLine2 = null)
    {
        $fullAddress = trim($addressLine1 . ' ' . ($addressLine2 ?? ''));
        $wrappedAddress = wordwrap($fullAddress, 35, "\n", true);
        $lines = explode("\n", $wrappedAddress);
        return array_slice($lines, 0, 2);
    }
}
