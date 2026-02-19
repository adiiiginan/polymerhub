<?php

namespace App\Services;

use App\Models\LionLog;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LionParcelService
{
    protected $apiUrl;
    protected $apiKey;

    protected $basicAuth;

    public function __construct()
    {
        $this->apiUrl = config('services.lionparcel.api_url');
        $this->apiKey = config('services.lionparcel.api_key');
        $this->basicAuth = config('services.lionparcel.basic_auth');
    }

    private function resolveResponse($response)
    {
        if ($response instanceof PromiseInterface) {
            return $response->wait();
        }
        return $response;
    }



    public function createShipment(array $payload)
    {
        Log::info('LION_CREATE_SHIPMENT_PAYLOAD', $payload);

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->basicAuth,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '/v2/client/shipment/create', $payload);


        $response = $this->resolveResponse($response);

        LionLog::create([
            'endpoint' => '/v2/client/shipment/create',
            'request_json' => json_encode($payload),
            'response_json' => $response->body(),
            'status_code' => $response->status(),
        ]);

        if ($response->failed()) {
            Log::error('LION_CREATE_SHIPMENT_FAILED', [
                'payload' => $payload,
                'response' => $response->json(),
            ]);
        } else {
            Log::info('LION_CREATE_SHIPMENT_SUCCESS', [
                'payload' => $payload,
                'response' => $response->json(),
            ]);
        }

        return $response;
    }

    public function getRates(array $data)
    {
        $userAddress = $data['user_address'];

        $origin = strtoupper(trim(config('services.lionparcel.origin')));
        $destination = strtoupper(trim($userAddress->kecamatan)) . ', ' . strtoupper(trim($userAddress->kota));

        $queryParams = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $data['weight'], // pastikan gram
            'commodity' => 'BPI087',
            'length' => 1,
            'width' => 1,
            'height' => 1,
        ];

        Log::info('LION_V3_TARIFF_REQUEST', $queryParams);

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->basicAuth,
            'Accept' => 'application/json',
        ])->get($this->apiUrl . '/v3/tariff', $queryParams);

        LionLog::create([
            'endpoint' => '/v3/tariff',
            'request_json' => json_encode($queryParams),
            'response_json' => $response->body(),
            'status_code' => $response->status(),
        ]);

        return $response;
    }
}
