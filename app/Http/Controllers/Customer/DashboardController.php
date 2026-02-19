<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\FedExController;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddress;
use App\Models\States;
use App\Models\Transaksi;
use App\Models\TransaksiInvoice;
use App\Models\TransaksiRequest;
use App\Models\Countries;
use App\Models\State;
use App\Models\City;
use App\Models\Cities;
use App\Models\Customer;
use App\Models\CourierCities;
use App\Models\CourierDistrict;
use App\Models\LionKecamatan;
use App\Models\LionKelurahan;
use App\Models\LionKodePos;
use App\Models\LionKota;
use App\Models\LionProvinsi;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::guard('customer')->user();
        $userId = $user->id;

        $baseQuery = Transaksi::where('iduser', $userId);

        $totalOrders = (clone $baseQuery)->count();
        $pendingOrders = (clone $baseQuery)->where('status', 1)->count();
        $poOrders = (clone $baseQuery)->where('status', 2)->count();
        $totalSpent = (clone $baseQuery)->whereHas('invoice', function ($q) {
            $q->whereIn('status', [7, 8, 9]);
        })->sum('total');

        $activeOrders = Transaksi::with(['items', 'address'])
            ->where('iduser', $userId)
            ->where('status', 1)
            ->select('transaksi.*') // Explicitly select shipping_service
            ->latest()
            ->paginate(10, ['*'], 'active_orders_page');
        $activeOrdersCount = $activeOrders->total();

        $completedOrdersHistory = Transaksi::with(['items', 'address'])
            ->where('iduser', $userId)
            ->whereIn('status', [4])
            ->latest()
            ->paginate(10, ['*'], 'completed_orders_page');
        $completedOrdersCount = $completedOrdersHistory->total();

        $approvedPoOrders = Transaksi::with(['items', 'user'])
            ->where('iduser', $userId)
            ->where('status', 2)
            ->latest()
            ->get();

        $invoicesQuery = TransaksiInvoice::whereHas('transaksi', function ($q) use ($userId) {
            $q->where('iduser', $userId);
        })->whereIn('status', [7, 8, 9]);

        $invoiceOrders = (clone $invoicesQuery)->count();
        $invoices = (clone $invoicesQuery)
            ->with(['transaksi', 'user'])
            ->latest()
            ->paginate(5, ['*'], 'invoice_page');

        $paymentPendingInvoices = TransaksiInvoice::whereHas('transaksi', function ($q) use ($userId) {
            $q->where('iduser', $userId);
        })->where('status', 7)
            ->with(['transaksi.items.produk'])
            ->latest()
            ->paginate(10, ['*'], 'payment_pending_page');

        $paymentPaidInvoices = TransaksiInvoice::whereHas('transaksi', function ($q) use ($userId) {
            $q->where('iduser', $userId);
        })->whereIn('status', [8, 9])
            ->with(['transaksi.items.produk'])
            ->latest()
            ->paginate(10, ['*'], 'payment_paid_page');
        $paidOrdersCount = $paymentPaidInvoices->total();

        $customOrders = TransaksiRequest::where('iduser', $userId)
            ->latest()
            ->paginate(10, ['*'], 'custom_orders_page');
        $customOrdersCount = $customOrders->total();

        $shippedOrders = Transaksi::with(['items', 'shipment', 'address'])
            ->where('iduser', $userId)
            ->where('status', 3)
            ->latest()
            ->paginate(10, ['*'], 'shipped_orders_page');
        $shippedOrdersCount = $shippedOrders->total();

        // 🔹 Cek apakah sudah lebih dari 7 hari dari tanggal dikirim
        foreach ($shippedOrders as $order) {
            if ($order->updated_at->diffInDays(now()) >= 7) {
                $order->{'status'} = 4; // otomatis ubah ke "Selesai"
                $order->save();
            }
        }


        if (request()->segment(1) == 'id') {
            return view('id.customer.dashboard', compact(
                'user',
                'totalOrders',
                'pendingOrders',
                'poOrders',
                'totalSpent',
                'activeOrders',
                'completedOrdersHistory',
                'activeOrdersCount',
                'approvedPoOrders',
                'invoiceOrders',
                'invoices',
                'paymentPendingInvoices',
                'paymentPaidInvoices',
                'paidOrdersCount',
                'customOrders',
                'customOrdersCount',
                'shippedOrders',
                'shippedOrdersCount'
            ));
        }
        return view('en.customer.dashboard', compact(
            'user',
            'totalOrders',
            'pendingOrders',
            'poOrders',
            'totalSpent',
            'activeOrders',
            'completedOrdersHistory',
            'activeOrdersCount',
            'approvedPoOrders',
            'invoiceOrders',
            'invoices',
            'paymentPendingInvoices',
            'paymentPaidInvoices',
            'paidOrdersCount',
            'customOrders',
            'customOrdersCount',
            'shippedOrders',
            'shippedOrdersCount'
        ));
    }

    public function showInvoice($id)
    {
        $invoice = TransaksiInvoice::with([
            'transaksi.address',
            'transaksi.user.userDetail',
            'transaksi.details.produk'
        ])->findOrFail($id);

        // Pastikan customer hanya bisa melihat invoice miliknya
        if ($invoice->transaksi->iduser !== Auth::guard('customer')->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        return view('en.customer.invoice.show', compact('invoice'));
    }

    public function uploadBukti(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:transaksi_invoice,id',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $invoice = TransaksiInvoice::findOrFail($request->invoice_id);

        // Pastikan invoice milik user yang sedang login
        if ($invoice->transaksi->iduser != auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'backend/assets/media/bukti';
            $file->move(public_path($path), $filename);

            $invoice->bukti_bayar = $path . '/' . $filename;
            $invoice->save();

            return redirect()->back()->with('success', 'Payment proof uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload payment proof.');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = \App\Models\Customer::find(Auth::guard('customer')->id());
        $user->load('addresses', 'detail.country');

        $countries = \App\Models\Countries::all();
        $states = collect();
        if ($user->detail->country) {
            $states = \App\Models\States::where('country_code', $user->detail->country->iso2)->get();
        }

        $courierCities = CourierCities::all();
        $provinsi = LionProvinsi::all();
        $kota = LionKota::all();
        $kecamatan = LionKecamatan::all();
        $kelurahan = LionKelurahan::all();
        $kode_pos = LionKodePos::all();

        if (request()->segment(1) == 'id') {
            return view('id.customer.profile', compact('user', 'countries', 'states', 'courierCities', 'provinsi', 'kota', 'kecamatan', 'kelurahan', 'kode_pos'));
        }

        return view('en.customer.profile', compact('user', 'countries', 'states'));
    }

    public function getCitiesByState(Request $request, $country_code, $state_code)
    {
        try {
            $cities = \App\Models\Cities::where('country_code', $country_code)
                ->where('state_code', $state_code)
                ->get();

            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDistricts(Request $request)
    {
        $cityId = $request->input('city_id');
        $districts = CourierDistrict::where('city_id', $cityId)->get();
        return response()->json($districts);
    }



    public function getZipByCountry(Request $request)
    {
        $countryCode = $request->input('country_code');
        $postalData = \App\Models\FedexPostal::where('country_code', $countryCode)->get(['begin_postal_code']);

        return response()->json($postalData);
    }

    public function getStates($country_code)
    {
        Log::info('getStates called with country_code: ' . $country_code);
        try {
            $states = States::where('country_code', $country_code)->get(['id', 'state_name', 'state_code']);
            Log::info('Found ' . $states->count() . ' states for country_code: ' . $country_code);
            return response()->json($states);
        } catch (\Exception $e) {
            Log::error('Error in getStates: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch states.', 'message' => $e->getMessage()], 500);
        }
    }

    public function getZipCode($cityId)
    {
        $postalCode = \App\Models\FedexPostal::where('id', $cityId)->first();
        return response()->json($postalCode->postal_code ?? '');
    }

    public function createAddress()
    {
        $countries = \App\Models\Negara::all();
        if (request()->segment(1) == 'id') {
            return view('id.customer.address.create', compact('countries'));
        }
        return view('en.customer.address.create', compact('countries'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alamat' => 'required|string',
            'idcountry' => 'required|exists:mysql.countries,id',
            'state' => 'required|exists:mysql.states,state_code',
            'city' => 'required|exists:mysql.cities,id',
            'zip_code' => 'required|string|max:10',
            'address_type' => 'required|string',
        ]);

        // Prepare data for FedEx validation
        $country = \App\Models\Countries::on('mysql')->findOrFail($request->idcountry);
        $city = \App\Models\Cities::on('mysql')->findOrFail($request->city);

        $validationRequest = new Request([
            'address1' => $request->alamat,
            'city' => $city->name,
            'stateOrProvinceCode' => $request->state,
            'postalCode' => $request->zip_code,
            'countryCode' => $country->iso2,
        ]);

        // Call FedEx validation
        $fedexController = new FedExController();
        $validationResponse = $fedexController->validateAddress($validationRequest);
        $validationData = $validationResponse->getData(true);

        // Check if validation was successful
        if ($validationResponse->status() != 200 || !isset($validationData['output']['resolvedAddresses'][0]['attributes']['Matched']) || $validationData['output']['resolvedAddresses'][0]['attributes']['Matched'] !== 'true') {
            return redirect()->back()->withInput()->with('error', 'FedEx address validation failed. Please check your address.');
        }

        $customer = Auth::guard('customer')->user();
        $is_primary = UserAddress::where('user_id', $customer->id)->count() == 0 ? 1 : 0;

        UserAddress::create([
            'user_id' => $customer->id,
            'nama' => $request->nama,
            'phone' => $request->phone,
            'alamat' => $request->alamat,
            'city' => $city->name,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'idcountry' => $request->idcountry,
            'kode_iso' => $country->iso2,
            'jenis_alamat' => $request->address_type,
            'is_primary' => $is_primary,
        ]);

        $redirectRoute = request()->segment(1) == 'id' ? 'id.customer.profile' : 'en.customer.profile';

        return redirect()->route($redirectRoute)->with('success', 'Address added successfully.');
    }

    public function editAddress(UserAddress $address)
    {
        $countries = \App\Models\Negara::all();
        if (request()->segment(1) == 'id') {
            return view('id.customer.address.edit', compact('address', 'countries'));
        }
        return view('en.customer.address.edit', compact('address', 'countries'));
    }

    public function updateAddress(Request $request, UserAddress $address)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alamat' => 'required|string',
            'idcountry' => 'required|exists:mysql.master_negara,id',
            'state' => 'required|exists:mysql.master_states,state_code',
            'city' => 'required|exists:mysql.master_cities,id',
            'zip_code' => 'required|string|max:10',
            'address_type' => 'required|string',
        ]);

        // Tentukan koneksi database secara eksplisit
        $connection = 'mysql';

        // Ambil data negara dan kota dari koneksi yang benar
        $country = \App\Models\Negara::on($connection)->findOrFail($request->idcountry);
        $city = \App\Models\Cities::on($connection)->findOrFail($request->city);

        $address->update([
            'nama' => $request->nama,
            'phone' => $request->phone,
            'alamat' => $request->alamat,
            'city' => $city->name, // Simpan NAMA kota
            'state' => $request->state, // Simpan KODE state
            'zip_code' => $request->zip_code,
            'idcountry' => $request->idcountry,
            'kode_iso' => $country->iso2, // Simpan KODE ISO negara
            'jenis_alamat' => $request->address_type,
        ]);

        $redirectRoute = request()->segment(1) == 'id' ? 'id.customer.profile' : 'en.customer.profile';

        return redirect()->route($redirectRoute)->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroyAddress(UserAddress $address)
    {
        $address->delete();

        $redirectRoute = request()->segment(1) == 'id' ? 'id.customer.profile' : 'en.customer.profile';

        return redirect()->route($redirectRoute)->with('success', 'Alamat berhasil dihapus.');
    }

    public function setPrimaryAddress(UserAddress $address)
    {
        $customer = Auth::guard('customer')->user();

        // Set all other addresses to not primary
        UserAddress::where('user_id', $customer->id)->update(['is_primary' => 0]);

        // Set the selected address to primary
        $address->update(['is_primary' => 1]);

        $redirectRoute = request()->segment(1) == 'id' ? 'id.customer.profile' : 'en.customer.profile';

        return redirect()->route($redirectRoute)->with('success', 'Alamat utama berhasil diubah.');
    }

    public function validateAddress(Request $request)
    {
        $apiKey = env('FEDEX_API_KEY');
        $apiSecret = env('FEDEX_SECRET_KEY');

        if (!$apiKey || !$apiSecret) {
            return response()->json(['error' => 'API credentials for FedEx are not set.'], 500);
        }

        $client = new \GuzzleHttp\Client();

        try {
            // Get OAuth Token
            $tokenResponse = $client->post('https://apis-sandbox.fedex.com/oauth/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $apiKey,
                    'client_secret' => $apiSecret,
                ],
            ]);

            $tokenData = json_decode($tokenResponse->getBody()->getContents(), true);
            $accessToken = $tokenData['access_token'];

            // Validate Address
            $address = [
                "streetLines" => [$request->input('address1'), $request->input('address2')],
                "city" => $request->input('city'),
                "stateOrProvinceCode" => $request->input('stateOrProvinceCode'),
                "postalCode" => $request->input('postalCode'),
                "countryCode" => $request->input('countryCode'),
            ];

            $validationResponse = $client->post('https://apis-sandbox.fedex.com/address/v1/addresses/resolve', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-locale' => 'en_US',
                ],
                'json' => [
                    'addressesToValidate' => [$address],
                ],
            ]);

            $validationData = json_decode($validationResponse->getBody()->getContents(), true);

            return response()->json($validationData);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return response()->json(json_decode($responseBodyAsString, true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function receiveOrder(Request $request, $id)
    {
        $user = Auth::guard('customer')->user();
        $transaksi = Transaksi::where('id', $id)
            ->where('iduser', $user->id)
            ->firstOrFail();

        // Pastikan order dalam status 'shipped' (status 3) sebelum diubah
        if ($transaksi->status == 3) {
            $transaksi->status = 4; // Ubah status ke 'Completed'
            $transaksi->save();

            return redirect()->back()->with('success', 'Order has been marked as received.');
        }

        return redirect()->back()->with('error', 'Unable to mark order as received.');
    }


    public function buatalamat(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alamat' => 'required|string',
            'provinsi' => 'required|exists:lion_provinsi,id',
            'kota' => 'required|exists:lion_kota,id',
            'kecamatan' => 'required|exists:lion_kecamatan,id',
            'kelurahan' => 'required|exists:lion_kelurahan,id',
            'zip_code' => 'required|string|max:10',
            'address_type' => 'required|string',
        ]);

        $customer = Auth::guard('customer')->user();
        $is_primary = UserAddress::where('user_id', $customer->id)->count() == 0 ? 1 : 0;

        // Ambil nama berdasarkan ID
        $provinsi = LionProvinsi::find($request->provinsi)->nama;
        $kota = LionKota::find($request->kota)->nama;
        $kecamatan = LionKecamatan::find($request->kecamatan)->nama;
        $kelurahan = LionKelurahan::find($request->kelurahan)->nama;

        UserAddress::create([
            'user_id' => $customer->id,
            'nama' => $request->nama,
            'phone' => $request->phone,
            'alamat' => $request->alamat,
            'provinsi' => $provinsi,
            'kota' => $kota,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'city' => $kota,
            'zip_code' => $request->zip_code,
            'jenis_alamat' => $request->address_type,
            'is_primary' => $is_primary,
            'idcountry' => 77, // Hardcode Indonesia
            'kode_iso' => 'ID', // Hardcode Indonesia
        ]);

        return redirect()->route('id.customer.profile')->with('success', 'Address added successfully.');
    }
}
