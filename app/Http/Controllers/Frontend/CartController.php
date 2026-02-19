<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\FedExController;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\FedexLog;
use App\Models\Jenis;
use App\Models\LionLog;
use App\Models\Perusahaan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Ukuran;
use App\Models\UserAddress;
use App\Services\LionParcelService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = null;
        $shippingRate = null;
        $primaryAddress = null;
        $allAddresses = [];
        $final_gross_weight = 0;
        $total_value = 0;
        $subtotal = 0;

        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            Log::info('Cart Index: Checking cart for user ' . $customer->id);
            $cart = Cart::where('iduser', $customer->id)->where('status', 1)->first();
            if ($cart) {
                Log::info('Cart Index: Found cart ' . $cart->id . ' for user ' . $customer->id, $cart->toArray());
                $cart->load('items.produk', 'items.jenis', 'items.ukuran');
                $final_gross_weight = $cart->items->sum('gros');
                $subtotal = $cart->items->sum(
                    function ($item) {
                        return $item->harga * $item->qty;
                    }
                );
                $total_value = $subtotal;
            } else {
                Log::info('Cart Index: No active cart found for user ' . $customer->id);
            }

            $allAddresses = DB::table('user_addresses')->where('user_id', $customer->id)->get();

            $selectedAddressId = session('selected_address_id');
            if ($selectedAddressId) {
                $primaryAddress = $allAddresses->firstWhere('id', $selectedAddressId);
            } elseif ($cart && $cart->address_id) {
                $primaryAddress = $allAddresses->firstWhere('id', $cart->address_id);
            } else {
                $primaryAddress = DB::table('user_addresses')
                    ->where('user_id', $customer->id)
                    ->orderBy('id', 'asc')
                    ->first();
            }

            if ($primaryAddress && $cart) {
                $cart->address_id = $primaryAddress->id;
                $cart->save();
            }

            if (Auth::guard('customer')->check() && $cart && !$cart->items->isEmpty() && !$primaryAddress) {
                $locale = request()->segment(1);
                $route = $locale === 'id' ? 'id.frontend.profile' : 'en.frontend.profile';
                $message = $locale === 'id' ? 'Silakan tambahkan alamat pengiriman untuk melanjutkan.' : 'Please add a shipping address to proceed.';

                return redirect()->route($route)->with('error', $message);
            }
        } else {
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart)) {
                $items = collect($sessionCart)->map(function ($item) {
                    return (object)[
                        'id' => $item['idproduk'] . '-' . $item['id_jenis'] . '-' . $item['id_ukuran'],
                        'idproduk' => $item['idproduk'],
                        'id_jenis' => $item['id_jenis'],
                        'id_ukuran' => $item['id_ukuran'],
                        'qty' => $item['qty'],
                        'harga' => $item['price'] ?? 0,
                        'gros' => $item['gros'] ?? 0,
                        'produk' => (object)[
                            'nama_produk' => $item['name'],
                            'gambar' => $item['image'],
                            'deskripsi' => ''
                        ],
                        'jenis' => (object)['jenis' => $item['jenis_name']],
                        'ukuran' => (object)['nama_ukuran' => $item['ukuran_name']],
                    ];
                });

                $cart = (object)[
                    'items' => $items,
                    'total' => $items->sum(function ($item) {
                        return $item->qty * $item->harga;
                    })
                ];

                $final_gross_weight = $items->sum('gros');
                $subtotal = $cart->total;
            }
        }

        if (Auth::guard('customer')->check() && $cart && !$cart->items->isEmpty() && !$primaryAddress) {
            $locale = request()->segment(1);
            $route = $locale === 'id' ? 'id.frontend.profile' : 'en.frontend.profile';
            $message = $locale === 'id' ? 'Silakan tambahkan alamat pengiriman untuk melanjutkan.' : 'Please add a shipping address to proceed.';

            return redirect()->route($route)->with('error', $message);
        }

        $viewData = compact('cart', 'primaryAddress', 'allAddresses', 'subtotal', 'final_gross_weight', 'shippingRate');

        if (request()->segment(1) == 'en') {
            return view('en.frontend.cart', $viewData);
        }

        if (request()->segment(1) == 'id') {
            return view('id.frontend.cart', $viewData);
        }

        return view('frontend.cart', $viewData);
    }

    public function showTransaksi(Transaksi $transaksi)
    {
        return view('id.frontend.transaksi.show', compact('transaksi'));
    }

    public function selectAddress($id)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $customer = Auth::guard('customer')->user();
        $address = DB::table('user_addresses')->where('id', $id)->where('user_id', $customer->id)->first();

        if (!$address) {
            return response()->json(['error' => 'Address not found.'], 404);
        }

        session(['selected_address_id' => $id]);

        return response()->json(['success' => true, 'message' => 'Address selected successfully.']);
    }

    public function getShippingRate(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $address = DB::table('user_addresses')->where('id', $request->address_id)->where('user_id', $customer->id)->first();
        if (!$address) {
            return response()->json(['error' => 'Invalid address selected.'], 400);
        }

        $cart = Cart::where('iduser', $customer->id)->where('status', 1)->first();
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty.'], 400);
        }

        $cart->address_id = $address->id;
        $cart->save();

        $total_weight = $cart->items->sum(fn($item) => $item->gros * $item->qty);
        $subtotal = $cart->items->sum(fn($item) => $item->harga * $item->qty);

        $shippingRequest = new Request([
            'destinationZip' => $address->zip_code,
            'destinationCountry' => $address->kode_iso,
            'destinationCity' => $address->city,
            'state' => $address->state,
            'destinationStreet' => $address->alamat,
            'totalWeight' => $total_weight,
            'totalValue' => $subtotal,
            'items' => $cart->items->toArray()
        ]);

        $fedexController = new FedExController();
        $shippingResponse = $fedexController->getRates($shippingRequest);

        if ($shippingResponse->isSuccessful()) {
            return response()->json($shippingResponse->getData(true));
        }

        return response()->json(['error' => 'Could not retrieve shipping rates.'], 500);
    }





    public function saveAddress(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $address = DB::table('user_addresses')->where('id', $request->address_id)->where('user_id', $customer->id)->first();
        if (!$address) {
            return response()->json(['error' => 'Invalid address selected.'], 400);
        }

        $cart = Cart::where('iduser', $customer->id)->where('status', 1)->first();
        if ($cart) {
            $cart->address_id = $request->address_id;
            $cart->save();
        }

        return response()->json(['success' => true]);
    }

    private function calculateGrossWeight($weight, $length, $width, $height)
    {
        $dimWeight = ($length * $width * $height) / 139;
        $gross = max($weight, $dimWeight);
        return round($gross, 2);
    }

    public function store(Request $request)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'product_stok_id' => 'required|exists:produk_stok,id',
        ]);

        try {
            $variant = DB::table('produk_stok')->find($request->product_stok_id);

            if (!$variant) {
                return response()->json(['success' => false, 'message' => 'Product variant not found.']);
            }

            if ($variant->stok < $request->qty) {
                return response()->json(['success' => false, 'message' => 'Insufficient stock for the requested quantity.']);
            }

            $product = \App\Models\Produk::findOrFail($variant->id_produk);
            $price = (session('user_country') == 'ID') ? $variant->harga : $variant->hargi;

            if (Auth::guard('customer')->check()) {
                $customer = Auth::guard('customer')->user();
                $cart = \App\Models\Cart::firstOrCreate(
                    ['iduser' => $customer->id, 'status' => 1],
                    ['iduser' => $customer->id, 'status' => 1]
                );

                $cartItemQuery = \App\Models\CartItem::where('idcart', $cart->id)
                    ->where('idproduk', $product->id)
                    ->where('id_jenis', $variant->id_jenis);

                if ($variant->id_ukuran) {
                    $cartItemQuery->where('id_ukuran', $variant->id_ukuran);
                } else {
                    $cartItemQuery->whereNull('id_ukuran');
                }
                $cartItem = $cartItemQuery->first();


                if ($cartItem) {
                    $cartItem->qty += $request->qty;
                    $cartItem->save();
                } else {
                    \App\Models\CartItem::create([
                        'idcart' => $cart->id,
                        'idproduk' => $product->id,
                        'id_jenis' => $variant->id_jenis,
                        'id_ukuran' => $variant->id_ukuran,
                        'qty' => $request->qty,
                        'harga' => $price,
                        'gros' => $variant->weight,
                    ]);
                }
            } else {
                $cart = session()->get('cart', []);
                $cartItemId = $variant->id;

                if (isset($cart[$cartItemId])) {
                    $cart[$cartItemId]['qty'] += $request->qty;
                } else {
                    $cart[$cartItemId] = [
                        "product_stok_id" => $variant->id,
                        "name" => $product->nama,
                        "qty" => $request->qty,
                        "price" => $price,
                        "weight" => $variant->weight,
                        "photo" => $product->gambar,
                        "id_jenis" => $variant->id_jenis,
                        "id_ukuran" => $variant->id_ukuran,
                        "id_produk" => $product->id,
                    ];
                }
                session()->put('cart', $cart);
            }


            return response()->json(['success' => true, 'message' => 'Product added to cart successfully.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Product not found.']);
        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add product to cart. Please try again.']);
        }
    }


    public function storeLion(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login untuk menambahkan produk ke keranjang.',
                'redirect' => route('id.customer.login')
            ]);
        }

        $request->validate([
            'qty' => 'required|integer|min:1',
            'product_stok_id' => 'required|exists:produk_stok,id',
        ]);

        $variant = DB::table('produk_stok')->find($request->product_stok_id);

        if (!$variant) {
            return response()->json(['message' => 'Product variant not found.'], 404);
        }

        $price = (float) ($variant->hargi ?? 0);

        DB::beginTransaction();
        try {
            $user = Auth::guard('customer')->user();
            $cart = Cart::firstOrCreate(
                ['iduser' => $user->id, 'status' => 1],
                ['total' => 0.00]
            );

            $itemAttributes = [
                'idcart' => $cart->id,
                'idproduk' => $variant->id_produk,
            ];

            if ($variant->id_jenis) {
                $itemAttributes['id_jenis'] = $variant->id_jenis;
            }

            if ($variant->id_ukuran) {
                $itemAttributes['id_ukuran'] = $variant->id_ukuran;
            }

            $item = CartItem::firstOrNew($itemAttributes);

            $item->qty = ($item->exists ? $item->qty : 0) + (int) $request->qty;
            $item->harga = $price;
            $item->weight = $variant->weight ?? 0;
            $item->length = $variant->length ?? 0;
            $item->width = $variant->width ?? 0;
            $item->height = $variant->height ?? 0;

            $item_weight = (float)($variant->weight ?? 0);
            $item_length = (float)($variant->length ?? 0);
            $item_width  = (float)($variant->width ?? 0);
            $item_height = (float)($variant->height ?? 0);
            $packing_weight = 0.5;

            $total_actual_weight = $item_weight * $item->qty;
            $total_volume = ($item_length * $item_width * $item_height) * $item->qty;
            $total_dimensional_weight = $total_volume / 5000;

            $billable_weight = max($total_actual_weight, $total_dimensional_weight);

            $final_gross_weight = round($billable_weight + $packing_weight, 2);

            $item->gros = $final_gross_weight;
            $item->save();

            $this->updateCartTotal($cart);
            DB::commit();

            Log::info('StoreLion Success: Cart ' . $cart->id . ' for user ' . $user->id . ' committed to DB.', $cart->load('items')->toArray());

            return response()->json(['success' => true, 'message' => 'Produk Berhasil ditambahkan.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
            return response()->json(['message' => 'Gagal menambahkan produk ke keranjang.'], 500);
        }
    }

    protected function updateCartTotal(Cart $cart)
    {
        $total = 0;
        foreach ($cart->items as $item) {
            $total += $item->harga * $item->qty;
        }
        $cart->total = $total;
        $cart->save();
    }


    public function setAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
        ]);

        $customer = Auth::guard('customer')->user();
        $cart = Cart::where('iduser', $customer->id)->where('status', 1)->first();

        if ($cart) {
            // Update address and reset shipping information
            $cart->update([
                'address_id' => $request->address_id,
                'shipping_service' => null,
                'shipping_cost' => null,
                'shipping_currency' => null,
            ]);

            // Logging untuk memantau reset
            LionLog::create([
                'endpoint' => 'set_address_reset_shipping',
                'status_code' => 200,
                'request_json' => json_encode($request->all()),
                'response_json' => json_encode([
                    'message' => 'Shipping info was reset because address changed.',
                    'cart_id' => $cart->id,
                ]),
            ]);

            return response()->json(['success' => true, 'message' => 'Address updated. Please select a new shipping option.']);
        }

        return response()->json(['error' => 'Cart not found.'], 404);
    }

    public function setShipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:user_addresses,id',
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $customer = Auth::guard('customer')->user();
        // FIX: Pastikan hanya mengambil cart yang aktif (status = 1)
        $cart = Cart::where('iduser', $customer->id)->where('status', 1)->first();

        if ($cart) {
            try {
                $cart->update([
                    'address_id' => $request->address_id,
                    'shipping_service' => $request->shipping_service,
                    'shipping_cost' => $request->shipping_cost,
                    'shipping_currency' => 'IDR',
                ]);

                // Logging untuk konfirmasi
                LionLog::create([
                    'endpoint' => 'set_shipping_success',
                    'status_code' => 200,
                    'request_json' => json_encode($request->all()),
                    'response_json' => json_encode([
                        'message' => 'Shipping info successfully updated in database.',
                        'cart_id' => $cart->id,
                        'new_shipping_cost' => $request->shipping_cost
                    ]),
                ]);

                return response()->json(['success' => true, 'message' => 'Shipping information saved.']);
            } catch (\Exception $e) {
                // Logging jika update gagal
                LionLog::create([
                    'endpoint' => 'set_shipping_failure',
                    'status_code' => 500,
                    'request_json' => json_encode($request->all()),
                    'response_json' => json_encode([
                        'message' => 'Failed to update cart in database.',
                        'error' => $e->getMessage(),
                        'cart_id' => $cart->id,
                    ]),
                ]);
                return response()->json(['success' => false, 'message' => 'Failed to save shipping information.'], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Active cart not found.'], 404);
    }

    public function saveShippingDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:user_addresses,id',
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
        }

        $cart = Cart::where('iduser', $customer->id)->first();
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found.'], 404);
        }

        // Ensure the address belongs to the user
        $userAddress = UserAddress::where('id', $request->address_id)->where('user_id', $customer->id)->first();
        if (!$userAddress) {
            // This is a critical check. If it fails, there's a data integrity issue.
            // For now, we log it and proceed, but this should be investigated.
            Log::warning('Potential data integrity issue: Customer ' . $customer->id . ' tried to use address ' . $request->address_id . ' which may not belong to them.');
        }

        $cart->shipping_service = $request->shipping_service;
        $cart->shipping_cost = $request->shipping_cost;
        $cart->address_id = $request->address_id; // Save address_id to cart

        if ($cart->save()) {
            $subtotal = $cart->items->sum(fn($item) => $item->harga * $item->qty);
            $total = $subtotal + $cart->shipping_cost;

            return response()->json([
                'success' => true,
                'message' => 'Shipping details saved.',
                'total' => number_format($total, 0, ',', '.')
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Failed to save shipping information.'], 500);
    }

    public function checkoutLionParcel(Request $request, LionParcelService $lionParcelService)
    {
        try {
            $customer = Auth::guard('customer')->user();
            if (!$customer) {
                return response()->json(['status' => 'error', 'message' => 'Customer not authenticated.'], 401);
            }

            $cart = Cart::where('iduser', $customer->id)->where('status', 1)->with('items.produk.variants')->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'Cart is empty or not found.'], 404);
            }

            if (!$cart->address_id || !$cart->shipping_service || !$cart->shipping_cost) {
                return response()->json(['status' => 'error', 'message' => 'Shipping details are incomplete. Please select an address and shipping service.'], 400);
            }

            $receiverAddress = UserAddress::find($cart->address_id);
            if (!$receiverAddress) {
                return response()->json(['status' => 'error', 'message' => 'Invalid shipping address stored in cart.'], 400);
            }

            DB::beginTransaction();

            $subtotal = $cart->items->sum(fn($item) => $item->harga * $item->qty);
            $total = $subtotal + $cart->shipping_cost;
            $idtransaksi = 'TRX-' . strtoupper(Str::random(10));

            $transaksi = Transaksi::create([
                'idtransaksi' => $idtransaksi,
                'user_id' => $customer->id,
                'status' => 1, // Pending
                'subtotal' => $subtotal,
                'shipping_cost' => $cart->shipping_cost,
                'shipping_service' => $cart->shipping_service,
                'shipping_currency' => 'IDR',
                'address_id' => $cart->address_id,
                'total' => $total,
            ]);

            foreach ($cart->items as $item) {
                TransaksiDetail::create([
                    'idtransaksi' => $idtransaksi,
                    'idproduk' => $item->idproduk,
                    'qty' => $item->qty,
                    'harga' => $item->harga,
                    'total' => $item->harga * $item->qty,
                ]);
            }

            $items = $cart->items->map(function ($item) {
                $variant = $item->produk->variants->where('id_ukuran', $item->id_ukuran)->first();
                // Convert weight from kg (stored in 'gros') to grams, ensuring it's an integer and at least 1.
                $weightInGrams = max(1, (int)round((float)($item->gros ?? 0) * 1000));

                return [
                    'price' => (float)$item->harga,
                    'weight' => $weightInGrams,
                    'name' => $item->produk->nama_produk,
                    'length' => (float)($variant->length ?? 1),
                    'width' => (float)($variant->width ?? 1),
                    'height' => (float)($variant->height ?? 1),
                ];
            })->toArray();

            // Format destination as KECAMATAN, KOTA/KABUPATEN in uppercase
            $origin_config = explode(',', config('services.lionparcel.shipper.origin'));
            $origin_kecamatan_nama = trim($origin_config[0]);
            $origin_kota_nama = trim($origin_config[1]);

            // -- DEBUGGING --
            dd([
                'LIONPARCEL_ORIGIN_ENV' => env('LIONPARCEL_ORIGIN'),
                'CONFIG_SERVICES_ORIGIN' => config('services.lionparcel.shipper.origin'),
                'PARSED_ORIGIN_KECAMATAN' => $origin_kecamatan_nama,
                'PARSED_ORIGIN_KOTA' => $origin_kota_nama,
                'DESTINATION_KECAMATAN' => $receiverAddress->kecamatan,
                'DESTINATION_KOTA' => $receiverAddress->city,
                'RECEIVER_ADDRESS_OBJECT' => $receiverAddress
            ]);
            // -- END DEBUGGING --

            $origin_area = \App\Models\LionKecamatan::where('nama', 'like', '%' . $origin_kecamatan_nama . '%')
                ->whereHas('kota', function ($q) use ($origin_kota_nama) {
                    $q->where('nama', 'like', '%' . $origin_kota_nama . '%');
                })->first();

            $destination_area = \App\Models\LionKecamatan::where('nama', 'like', '%' . $receiverAddress->kecamatan . '%')
                ->whereHas('kota', function ($q) use ($receiverAddress) {
                    $q->where('nama', 'like', '%' . $receiverAddress->city . '%');
                })->first();

            if (!$origin_area || !$destination_area) {
                return response()->json(['status' => 'error', 'message' => 'Origin or destination area code not found.'], 400);
            }

            $payload = [
                'shipment_type' => 'PICKUP',
                'service_code' => strtoupper($cart->shipping_service),
                'sender' => [
                    'name' => config('services.lionparcel.shipper.name'),
                    'phone' => config('services.lionparcel.shipper.phone'),
                    'address' => config('services.lionparcel.shipper.address'),
                    'post_code' => config('services.lionparcel.shipper.postal_code'),
                    'origin' => $origin_area->kode_area,
                    'email' => config('services.lionparcel.shipper.email'),
                    'geoloc' => config('services.lionparcel.shipper.geoloc', '-6.965797984607324, 107.54938870737607'),
                ],
                'receiver' => [
                    'name' => $receiverAddress->nama,
                    'phone' => $receiverAddress->phone,
                    'address' => $receiverAddress->alamat,
                    'post_code' => $receiverAddress->zip_code,
                    'destination' => $destination_area->kode_area,
                    'email' => $customer->email,
                    'geoloc' => '',
                ],
                'detail' => [
                    'use_insurance' => false,
                    'invoice_no' => $idtransaksi,
                    'commodity' => 'BPI087',
                    'is_cod' => false,
                    'is_dfod' => false,
                    'cod_value' => 0,
                    'items' => $items,
                ],
            ];

            Log::channel('lionparcel')->info('LION_CREATE_SHIPMENT_PAYLOAD', $payload);

            $response = $lionParcelService->createShipment($payload);

            if (isset($response['success']) && $response['success']) {
                $transaksi->update([
                    'lion_parcel_booking_id' => $response['data']['booking_id'] ?? null,
                    'lion_parcel_stt' => $response['data']['stt'] ?? null,
                    'lion_parcel_response' => json_encode($response),
                ]);

                // Create Lion Shipment record
                DB::table('lion_shipments')->insert([
                    'transaksi_id' => $transaksi->id,
                    'booking_id' => $response['data']['booking_id'] ?? null,
                    'stt' => $response['data']['stt'] ?? null,
                    'response_data' => json_encode($response),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $cart->items()->delete();
                $cart->delete();

                DB::commit();

                return response()->json(['status' => 'success', 'redirect_url' => route('id.frontend.checkout.success', $transaksi->idtransaksi)]);
            } else {
                DB::rollBack();

                LionLog::create([
                    'endpoint' => 'create_shipment_failure',
                    'request_json' => json_encode($payload),
                    'response_json' => json_encode($response),
                    'status_code' => $response['status_code'] ?? 500,
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to book shipment with Lion Parcel.',
                    'details' => $response
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('lionparcel')->error('CHECKOUT_LION_PARCEL_EXCEPTION', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred during checkout.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    public function success($idtransaksi)
    {
        $transaksi = Transaksi::where('idtransaksi', $idtransaksi)->firstOrFail();
        return view('id.frontend.transaksi.show', compact('transaksi'));
    }

    public function transaksi()
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('en.login')->with('error', 'Please login to view your transactions.');
        }

        $transaksiId = session()->pull('latest_transaksi_id');

        if (!$transaksiId) {
            return redirect()->route('en.home')->with('info', 'No recent transaction to display.');
        }

        $transaksi = Transaksi::where('id', $transaksiId)
            ->where('iduser', $customer->id)
            ->with(['details.produk', 'details.jenis', 'details.ukuran', 'address', 'user.userDetail'])
            ->first();

        if (!$transaksi) {
            return redirect()->route('en.home')->with('error', 'Transaction not found.');
        }

        return view('en.frontend.transaksi', compact('transaksi'));
    }

    public function transaksiIndo(Request $request)
    {
        Log::info('Entering transaksiIndo method.');
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        // FIX: Use the correct column 'iduser' instead of 'user_id'
        $cart = Cart::where('iduser', $customer->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang Anda kosong.'], 404);
        }

        if (is_null($cart->shipping_cost) || is_null($cart->shipping_service) || is_null($cart->address_id)) {
            Log::warning('Shipping information incomplete for cart: ' . $cart->id);
            return redirect()->route('id.frontend.cart.index')->with('error', 'Shipping information is incomplete. Please select a shipping option.');
        }

        $address = DB::table('user_addresses')
            ->where('id', $cart->address_id)
            ->where('user_id', $customer->id)
            ->first();

        if (!$address) {
            Log::warning('Address not found for cart: ' . $cart->id);
            return redirect()->route('id.frontend.cart.index')->with('error', 'Alamat pengiriman tidak valid.');
        }

        $subtotal = $cart->items->sum(fn($item) => $item->harga * $item->qty);

        $total = $subtotal;
        $idtransaksi = 'TRX-' . Str::upper(Str::random(8));

        Log::info('Creating transaction with id: ' . $idtransaksi);
        $transaksi = Transaksi::create([
            'idtransaksi' => $idtransaksi,
            'iduser' => $customer->id,
            'subtotal' => $subtotal,
            'address_id' => $cart->address_id,
            'total' => $total,
            'status' => 1,
        ]);
        Log::info('Transaction created successfully.');

        foreach ($cart->items as $item) {
            TransaksiDetail::create([
                'idtrans' => $transaksi->id,
                'idproduk' => $item->idproduk,
                'harga' => $item->harga,
                'qty' => $item->qty,
                'gros' => $item->gros,
                'id_jenis' => $item->id_jenis,
                'id_ukuran' => $item->id_ukuran,
                'subtotal' => $item->harga * $item->qty,
            ]);
        }
        Log::info('Transaction details created successfully.');

        $cart->status = 2;
        $cart->save();
        Log::info('Cart status updated to checked out.');

        DB::commit();
        Log::info('Database transaction committed.');

        return redirect()->route('id.frontend.transaksi.show', ['transaksi' => $transaksi->id])
            ->with('success', 'Checkout successful!');
    }

    public function updateShippingSelection(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = Cart::where('iduser', $customer->id)->where('status', 1)->first();
        if (!$cart) {
            return response()->json(['error' => 'No active cart found.'], 404);
        }

        $address = DB::table('user_addresses')->where('id', $cart->address_id)->where('user_id', $customer->id)->first();
        if (!$address) {
            return response()->json(['error' => 'Invalid address selected.'], 400);
        }

        $cart->shipping_service = $request->shipping_service;
        $cart->shipping_cost = $request->shipping_cost;
        $cart->save();

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    public function updateQuantity(Request $request, $item_id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:increase,decrease',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid action.'], 422);
        }

        $action = $request->input('action');

        if (Auth::guard('customer')->check()) {
            $cart = Cart::where('iduser', Auth::guard('customer')->id())->where('status', 1)->first();
            if (!$cart) {
                return response()->json(['success' => false, 'message' => 'Cart not found.'], 404);
            }

            $item = $cart->items()->find($item_id);
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
            }

            if ($action == 'increase') {
                $item->qty += 1;
            } elseif ($action == 'decrease') {
                if ($item->qty > 1) {
                    $item->qty -= 1;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Quantity cannot be less than 1.'
                    ], 400);
                }
            }
            $item->save();

            // Recalculate totals
            $this->updateCartTotal($cart);
            $cart->refresh(); // Refresh cart model to get the updated totals

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully.',
                'new_quantity' => $item->qty,
                'item_subtotal' => $item->harga * $item->qty,
                'cart_total' => $cart->total,
                'cart_total_gross_weight' => $cart->total_gross_weight,
            ]);
        } else {
            // Handle guest cart (session-based)
            $cart = session('cart', []);
            if (!isset($cart[$item_id])) {
                return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
            }

            if ($action == 'increase') {
                $cart[$item_id]['qty'] += 1;
            } elseif ($action == 'decrease') {
                if ($cart[$item_id]['qty'] > 1) {
                    $cart[$item_id]['qty'] -= 1;
                }
            }

            session(['cart' => $cart]);

            // Recalculate totals for guest cart
            $total = 0;
            $total_gross_weight = 0;
            foreach ($cart as $cart_item) {
                $total += $cart_item['price'] * $cart_item['qty'];
                $total_gross_weight += ($cart_item['gross_weight'] ?? 0) * $cart_item['qty'];
            }

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully.',
                'new_quantity' => $cart[$item_id]['qty'],
                'item_subtotal' => $cart[$item_id]['price'] * $cart[$item_id]['qty'],
                'cart_total' => $total,
                'cart_total_gross_weight' => $total_gross_weight,
            ]);
        }
    }

    public function destroy($itemId)
    {
        try {
            if (Auth::guard('customer')->check()) {
                $cartItem = CartItem::where('id', $itemId)
                    ->whereHas('cart', function ($query) {
                        $query->where('iduser', Auth::guard('customer')->id());
                    })
                    ->firstOrFail();
                $cartItem->delete();
            } else {
                $cart = session()->get('cart', []);
                if (isset($cart[$itemId])) {
                    unset($cart[$itemId]);
                    session()->put('cart', $cart);
                }
            }

            return redirect()->route('id.frontend.cart.index')->with('success', 'Product removed from cart.');
        } catch (\Exception $e) {
            Log::error('Failed to remove product from cart: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to remove product from cart. Please try again.');
        }
    }
}
