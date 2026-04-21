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
        $this->apiUrl    = config('services.lionparcel.api_url');
        $this->apiKey    = config('services.lionparcel.api_key');
        $this->basicAuth = config('services.lionparcel.basic_auth');
    }

    private function resolveResponse($response)
    {
        if ($response instanceof PromiseInterface) {
            return $response->wait();
        }
        return $response;
    }

    // -------------------------------------------------------------------------
    // Helper terpusat: simpan ke lion_logs sekaligus ke channel lionparcel
    // -------------------------------------------------------------------------
    private function writeLog(
        string $endpoint,
        array  $requestData,
        string $responseBody,
        int    $statusCode,
        string $status,         // 'success' | 'error'
        string $logLevel = 'info',
        array  $extra    = []
    ): void {
        // 1. Simpan ke tabel lion_logs
        try {
            LionLog::create([
                'endpoint'      => $endpoint,
                'request_json'  => json_encode($requestData),
                'response_json' => $responseBody,
                'status_code'   => (string) $statusCode,
                'status'        => $status,
            ]);
        } catch (\Throwable $e) {
            // Jangan sampai gagal log merusak flow utama
            Log::error('LION_LOG_DB_WRITE_FAILED', ['error' => $e->getMessage()]);
        }

        // 2. Tulis ke channel lionparcel (file log)
        $context = array_merge([
            'endpoint'    => $endpoint,
            'status_code' => $statusCode,
            'status'      => $status,
        ], $extra);

        match ($logLevel) {
            'error'   => Log::channel('lionparcel')->error(strtoupper(str_replace('/', '_', trim($endpoint, '/'))) . '_' . strtoupper($status), $context),
            'warning' => Log::channel('lionparcel')->warning(strtoupper(str_replace('/', '_', trim($endpoint, '/'))) . '_' . strtoupper($status), $context),
            default   => Log::channel('lionparcel')->info(strtoupper(str_replace('/', '_', trim($endpoint, '/'))) . '_' . strtoupper($status), $context),
        };
    }

    // =========================================================================
    // CREATE SHIPMENT
    // POST https://api-stg-middleware.thelionparcel.com/client/booking
    // =========================================================================
    public function createShipment(array $payload)
    {
        $endpoint = 'https://api-stg-middleware.thelionparcel.com/client/booking';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->basicAuth,
                'x-api-key'     => $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post($endpoint, $payload);

            $response = $this->resolveResponse($response);
        } catch (\Throwable $e) {
            $this->writeLog(
                $endpoint,
                $payload,
                json_encode(['exception' => $e->getMessage()]),
                0,
                'error',
                'error',
                ['payload' => $payload]
            );
            throw $e;
        }

        $status = $response->successful() ? 'success' : 'error';

        $this->writeLog(
            $endpoint,
            $payload,
            $response->body(),
            $response->status(),
            $status,
            $response->successful() ? 'info' : 'error',
            ['payload' => $payload, 'response' => $response->json()]
        );

        return $response->json();
    }

    // =========================================================================
    // GET RATES
    // GET /v3/tariff
    // =========================================================================
    public function getRates(array $data)
    {
        $userAddress = $data['user_address'];

        $origin      = strtoupper(trim(config('services.lionparcel.origin')));
        $destination = strtoupper(trim($userAddress->kecamatan)) . ', ' . strtoupper(trim($userAddress->kota));

        $queryParams = [
            'origin'      => $origin,
            'destination' => $destination,
            'weight'      => $data['weight'],
            'commodity'   => 'BPI087',
            'length'      => 1,
            'width'       => 1,
            'height'      => 1,
        ];

        $endpoint = '/v3/tariff';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->basicAuth,
                'x-api-key'     => $this->apiKey,
                'Accept'        => 'application/json',
            ])->get($this->apiUrl . $endpoint, $queryParams);
        } catch (\Throwable $e) {
            $this->writeLog(
                $endpoint,
                $queryParams,
                json_encode(['exception' => $e->getMessage()]),
                0,
                'error',
                'error',
                ['query' => $queryParams]
            );
            throw $e;
        }

        $status = $response->successful() ? 'success' : 'error';

        $this->writeLog(
            $endpoint,
            $queryParams,
            $response->body(),
            $response->status(),
            $status,
            $response->successful() ? 'info' : 'error',
            ['query' => $queryParams]
        );

        return $response;
    }

    // =========================================================================
    // GET STT DETAIL
    // GET /v3/stt/detail?q={stt_no}
    // =========================================================================
    public function getDetailSTT(string $sttNo): array
    {
        $endpoint    = '/v3/stt/detail';
        $requestData = ['q' => $sttNo];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->basicAuth,
                'x-api-key'     => $this->apiKey,
                'Accept'        => 'application/json',
            ])->get('https://api-stg-middleware.thelionparcel.com' . $endpoint, $requestData);
        } catch (\Throwable $e) {
            $this->writeLog(
                $endpoint,
                $requestData,
                json_encode(['exception' => $e->getMessage()]),
                0,
                'error',
                'error',
                ['stt_no' => $sttNo]
            );
            return ['success' => false, 'message' => $e->getMessage(), 'data' => null];
        }

        $status = $response->successful() ? 'success' : 'error';

        $this->writeLog(
            $endpoint,
            $requestData,
            $response->body(),
            $response->status(),
            $status,
            $response->successful() ? 'info' : 'error',
            ['stt_no' => $sttNo, 'response' => $response->json()]
        );

        if ($response->failed()) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail STT',
                'data'    => null,
            ];
        }

        return [
            'success' => true,
            'data'    => $response->json('stts'),
        ];
    }
}
