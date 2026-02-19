<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Klasifikasi;
use App\Models\Negara;
use App\Models\Kota;
use App\Models\UserDetail;
use App\Models\UserAddress;
use App\Services\KodeUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\AccountUnderReviewMail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $perusahaan = Perusahaan::all();
        $klasifikasi = Klasifikasi::all();
        $negara = Negara::all();
        return view('frontend.register', compact('perusahaan', 'klasifikasi', 'negara'));
    }

    public function submitRegistrationForm(Request $request, KodeUserService $kodeUserService)
    {
        $request->validate([
            'perusahaan' => 'required|string|max:255',
            'vat_number' => 'required|string|max:255',
            'idperusahaan' => 'required|exists:perusahaan,id',
            'idkuali' => 'required|exists:klasifikasi,id',
            'nama' => 'required|string|max:255',
            'lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:20',
            'jabatan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nama_kota' => 'required|exists:kota,id',
            'zip_code' => 'required|string|max:10',
            'idcountry' => 'required|exists:negara,id',
            'comment' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'agree' => 'required',
        ]);

        $kode_user = $kodeUserService->generate();
        $negara = Negara::find($request->idcountry);
        $kode_iso = $negara ? $negara->kode_iso : null;

        $user = User::create([
            'kode_user' => $kode_user,
            'username' => $request->email,
            'email' => $request->email,
            'password' => $request->password, // This will be hashed by the model
            'id_priviladges' => 3, // Default to User
            'status' => 2, // Default to Pending
            'comment' => $request->comment,
        ]);

        UserDetail::create([
            'kode_user' => $user->kode_user,
            'email' => $request->email,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'jabatan' => $request->jabatan,
            'alamat' => $request->alamat,
            'idkuali' => $request->idkuali,
            'idperusahaan' => $request->idperusahaan,
            'perusahaan' => $request->perusahaan,
            'lengkap' => $request->lengkap,
            'zip' => $request->zip_code,
            'city' => $request->nama_kota,
            'idcountry' => $request->idcountry,
            'vat' => $request->vat_number,
            'kode_iso' => $kode_iso,
        ]);

        // UserAddress::create([
        //     'user_id' => $user->id,
        //     'nama' => $request->nama,
        //     'phone' => $request->no_hp,
        //     'alamat' => $request->alamat,
        //     'city' => $request->nama_kota,
        //     'zip_code' => $request->zip_code,
        //     'idcountry' => $request->idcountry,
        //     'is_primary' => 1,
        // ]);

        // Send email notification
        Mail::to($user->email)->send(new AccountUnderReviewMail($user));

        return redirect()->route('register')->with('success', 'Registration successful! Please check your email for further instructions.');
    }

    public function getCities($countryId)
    {
        $cities = Kota::where('country_id', $countryId)->get();

        return response()->json($cities);
    }

    public function getZipCode($cityId)
    {
        $city = Kota::select('zip_code')->find($cityId);
        return response()->json($city);
    }
}
