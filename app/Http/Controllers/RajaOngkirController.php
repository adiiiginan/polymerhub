<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{

    public function testRajaOngkir()
    {
        $response = Http::withHeaders([
            'x-api-key' => env('RAJAONGKIR_API_KEY'),
            'Accept' => 'application/json',
        ])->get(env('RAJAONGKIR_BASE_URL') . '/shipping/provinces');

        return response()->json([
            'status' => $response->status(),
            'body'   => $response->json()
        ]);
    }
}
