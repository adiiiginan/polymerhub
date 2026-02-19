<?php

namespace App\Http\Controllers\Customer;

use App\Models\Countries;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\FedExController;

use App\Models\UserAddress;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('en.customer.profile');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Countries::all();
        return view('en.customer.address.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city = \App\Models\Cities::find($request->city);
        if (!$city) {
            return redirect()->route('en.customer.profile')->with('error', 'Invalid city selected.');
        }

        $country = \App\Models\Countries::find($request->idcountry);
        if (!$country) {
            return redirect()->route('en.customer.profile')->with('error', 'Invalid country selected.');
        }

        $state = \App\Models\States::where('state_code', $request->state)->first();
        if (!$state) {
            return redirect()->route('en.customer.profile')->with('error', 'Invalid state selected.');
        }

        $stateForFedex = null;

        if (in_array($country->iso2, ['US', 'CA', 'AU'])) {
            $stateForFedex = $state->state_code; // contoh: CA, NY
        }


        $fedexController = new FedExController();
        $validationRequest = new Request([
            'address1' => $request->alamat,
            'city' => $city->name,
            'stateOrProvinceCode' => $stateForFedex, // ← PAKAI INI
            'postalCode' => $request->zip_code,
            'countryCode' => $country->iso2,
        ]);

        // FedEx validation
        $validationResponse = $fedexController->validateAddress($validationRequest);
        $validationResult = json_decode($validationResponse->getContent(), true);

        // Handle error dari controller FedEx
        if (isset($validationResult['error'])) {
            return redirect()->route('en.customer.profile')
                ->with('error', $validationResult['error']);
        }

        $classification =
            $validationResult['output']['addressesToValidate'][0]['classification']
            ?? null;

        if (in_array($classification, ['UNDELIVERABLE', 'UNCONFIRMED'])) {
            return redirect()->route('en.customer.profile')
                ->with('error', 'Alamat tidak valid menurut FedEx');
        }


        // Also check for a general error from our own controller (e.g., token failure)
        if (isset($validationResult['error'])) {
            return redirect()->route('en.customer.profile')->with('error', $validationResult['error']);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alamat' => 'required|string',
            'idcountry' => 'required|exists:mysql.countries,id',
            'state' => 'required|exists:mysql.states,state_code',
            'city' => 'required|exists:mysql.cities,id',
            'zip_code' => 'required|string|max:10',
            'jenis_alamat' => 'required|string',
        ]);

        $is_primary = UserAddress::where('user_id', auth()->id())->count() == 0 ? 1 : 0;

        $dataToCreate = [
            'user_id' => auth()->id(),
            'nama' => $request->nama,
            'phone' => $request->phone,
            'alamat' => $request->alamat,
            'city' => $city->name,
            'state' => $state->state_name,
            'zip_code' => $request->zip_code,
            'idcountry' => $request->idcountry,
            'kode_iso' => $country->iso2,
            'jenis_alamat' => $request->jenis_alamat,
            'is_primary' => $is_primary,
        ];
        $dataToCreate['fedex_status'] = $classification;
        $dataToCreate['fedex_response'] = \json_encode($validationResult);
        UserAddress::create($dataToCreate);

        // Log the FedEx validation response
        Log::info('FedEx Address Validation Response:', [
            'user_id' => auth()->id(),
            'fedex_response' => $validationResult
        ]);

        return redirect()->route('en.customer.profile')->with('success', 'Address created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $address = UserAddress::findOrFail($id);
        return view('en.customer.address.edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alamat' => 'required|string',
            'idcountry' => 'required|exists:mysql.countries,id',
            'state' => 'required|exists:mysql.states,state_code',
            'city' => 'required|exists:mysql.cities,id',
            'zip_code' => 'required|string|max:10',
            'jenis_alamat' => 'required|string',
        ]);

        $address = UserAddress::findOrFail($id);

        $connection = 'mysql';

        $country = \App\Models\Countries::on($connection)->findOrFail($request->idcountry);
        $city = \App\Models\Cities::on($connection)->findOrFail($request->city);

        $dataToUpdate = [
            'nama' => $request->nama,
            'phone' => $request->phone,
            'alamat' => $request->alamat,
            'city' => $city->name,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'idcountry' => $request->idcountry,
            'kode_iso' => $country->iso2,
            'jenis_alamat' => $request->jenis_alamat,
        ];

        $address->update($dataToUpdate);

        return redirect()->route('en.customer.profile')->with('success', 'Address updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = UserAddress::findOrFail($id);
        $address->delete();

        return redirect()->route('en.customer.profile')->with('success', 'Address deleted successfully.');
    }
}
