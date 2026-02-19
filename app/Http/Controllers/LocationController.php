<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Countries;

class LocationController extends Controller
{
    public function getStates(Request $request, $countryCode)
    {
        $states = DB::table('cities')
            ->where('country_code', $countryCode)
            ->whereNotNull('state_code')
            ->distinct()
            ->get(['state_code', 'state_name']);

        return response()->json($states);
    }

    public function getCities(Request $request, $countryCode, $stateCode = null)
    {
        $query = DB::table('cities')->where('country_code', $countryCode);

        if ($stateCode) {
            $query->where('state_code', $stateCode);
        } else {
            $query->whereNull('state_code');
        }

        $cities = $query->get(['name']);

        return response()->json($cities);
    }

    public function getCountries()
    {
        $countries = Countries::all();
        return response()->json($countries);
    }

    public function hasStates($countryCode)
    {
        $hasStates = DB::table('cities')
            ->where('country_code', $countryCode)
            ->whereNotNull('state_code')
            ->exists();

        return response()->json(['has_states' => $hasStates]);
    }
}
