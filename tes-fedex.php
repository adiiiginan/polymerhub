<?php
// test_fedex.php — letakkan di C:\xampp\htdocs\asia\test_fedex.php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

echo "=== FedEx Config ===" . PHP_EOL;
echo "mode:         " . config('services.fedex.mode')                 . PHP_EOL;
echo "sandbox_url:  " . config('services.fedex.sandbox_url')          . PHP_EOL;
echo "client_id:    " . config('services.fedex.client_id')            . PHP_EOL;
echo "client_secret:" . config('services.fedex.client_secret')        . PHP_EOL;
echo "account:      " . config('services.fedex.account_number')       . PHP_EOL;
echo "postal_code:  " . config('services.fedex.shipper.postal_code')  . PHP_EOL;
echo "country_code: " . config('services.fedex.shipper.country_code') . PHP_EOL;
echo PHP_EOL;

echo "=== Test Token ===" . PHP_EOL;
$res = Http::asForm()->post('https://apis-sandbox.fedex.com/oauth/token', [
    'grant_type'    => 'client_credentials',
    'client_id'     => config('services.fedex.client_id'),
    'client_secret' => config('services.fedex.client_secret'),
]);
echo "HTTP Status: " . $res->status() . PHP_EOL;
echo "Body: "        . $res->body()   . PHP_EOL;
echo PHP_EOL;

echo "=== FedexLog (3 terbaru) ===" . PHP_EOL;
$logs = App\Models\FedexLog::latest()->take(3)->get();
foreach ($logs as $log) {
    echo "---" . PHP_EOL;
    echo "time:     " . $log->created_at   . PHP_EOL;
    echo "endpoint: " . $log->endpoint     . PHP_EOL;
    echo "status:   " . $log->status_code  . PHP_EOL;
    echo "response: " . substr($log->response_json, 0, 800) . PHP_EOL;
}

echo PHP_EOL;
echo "=== FedexService exists? ===" . PHP_EOL;
$path = __DIR__ . '/app/Services/FedexService.php';
echo file_exists($path) ? "YES — " . $path : "NO — file tidak ada!" . PHP_EOL;
