<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LionKodePos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class LionParcelController extends Controller
{
    protected \Illuminate\Http\Client\PendingRequest $apiClient;

    public function __construct()
    {
        $this->apiClient = Http::withHeaders([
            'Authorization' => 'Basic ' . config('services.lionparcel.basic_auth'),
            'Accept' => 'application/json',
        ])->baseUrl(config('services.lionparcel.api_url'));
    }

    public function getServices(Request $request)
    {
        Log::channel('lionparcel')->info('--- GETSERVICES START ---', ['request_all' => $request->all()]);
        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric|min:0.1',
            'address_id' => 'required|integer|exists:user_addresses,id',
        ], [
            'address_id.required' => 'Silakan pilih alamat pengiriman.',
            'address_id.exists' => 'Alamat yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $authedUser = Auth::guard('customer')->user();
        if (!$authedUser) {
            return response()->json(['success' => false, 'message' => 'Anda harus login untuk menggunakan layanan ini.'], 401);
        }

        // Re-fetch the user from the database to ensure it's a complete model
        $user = \App\Models\Customer::find($authedUser->id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        // Find the specific address for the authenticated user
        $address = $user->addresses()->where('id', $request->input('address_id'))->first();

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Alamat pengiriman tidak ditemukan atau tidak valid.'], 404);
        }

        if (empty($address->kecamatan) || empty($address->kota)) {
            return response()->json(['success' => false, 'message' => 'Alamat yang dipilih tidak lengkap. Pastikan kecamatan dan kota telah diisi.'], 400);
        }

        $weight = (float) $request->input('weight');
        $weight = round((float) $request->input('weight'), 2);

        $origin = config('lionparcel.origin');
        $destination = strtoupper($address->kecamatan . ', ' . $address->kota);

        $queryParams = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'commodity' => 'BPI087',
            'length' => (int) $request->input('length', 1),
            'width' => (int) $request->input('width', 1),
            'height' => (int) $request->input('height', 1),
        ];

        try {
            Log::channel('lionparcel')->info('LION_TARIFF_REQUEST', $queryParams);

            $response = $this->apiClient->timeout(30)->get('/v3/tariff', $queryParams);
            $apiData = $response->json(); // Baca sekali dan simpan di variabel

            if (!$response->successful()) {
                Log::channel('lionparcel')->error('LION_API_ERROR', [
                    'status' => $response->status(),
                    'body' => $apiData, // Gunakan variabel
                    'request' => $queryParams,
                ]);
                $errorMessage = $apiData['message'] ?? 'Gagal mendapatkan tarif dari Lion Parcel.';
                return response()->json(['success' => false, 'message' => $errorMessage], $response->status());
            }

            Log::channel('lionparcel')->info('LION_API_SUCCESS_RESPONSE', ['body' => $apiData]); // Gunakan variabel

            $results = $apiData['result'] ?? [];
            Log::channel('lionparcel')->info('Processing results.', ['count' => count($results)]);

            $services = [];
            if (!empty($results)) {
                foreach ($results as $item) {
                    Log::channel('lionparcel')->info('Inspecting item full.', $item);
                    // 1. Filter: Hanya proses item yang aktif dan merupakan paket
                    if (isset($item['status'], $item['service_type']) && $item['status'] === 'ACTIVE' && $item['service_type'] === 'PACKAGE') {

                        // 2. Mapping: Ubah ke format yang diinginkan frontend
                        if (isset($item['product'], $item['total_tariff'], $item['estimasi_sla'])) {
                            $services[] = [
                                'serviceCode' => $item['product'],
                                'serviceName' => $item['product'],
                                'serviceType' => $item['service_type'],
                                'price' => $item['total_tariff'],
                                'etd' => $item['estimasi_sla'] === '- Hari'
                                    ? null
                                    : $item['estimasi_sla'],
                            ];
                            Log::channel('lionparcel')->info('Service added.', ['product' => $item['product'], 'service_count' => count($services)]);
                        }
                    }
                }
            }

            Log::channel('lionparcel')->info('Final service count.', ['count' => count($services)]);

            // 3. Sorting: Urutkan dari harga termurah ke termahal
            if (!empty($services)) {
                usort($services, function ($a, $b) {
                    return $a['price'] <=> $b['price'];
                });
            }



            // 4. Fallback: Beri pesan jika tidak ada layanan yang tersedia
            if (empty($services)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Tidak ada opsi pengiriman yang tersedia untuk tujuan ini.',
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $services,
                'message' => 'Layanan berhasil diambil.',
            ]);
        } catch (Throwable $e) {
            Log::channel('lionparcel')->critical('LION_UNHANDLED_EXCEPTION', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $queryParams,
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan yang tidak terduga pada sistem.'], 500);
        }
    }
}
