<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\LionParcelWebhookController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Frontend\TrackingController;





Route::post('/webhook/lionparcel', [LionParcelWebhookController::class, 'handle']);

Route::get('/locations/countries', [LocationController::class, 'getCountries']);
Route::get('/locations/{countryCode}/has-states', [LocationController::class, 'hasStates']);
Route::get('/locations/{countryCode}/states', [LocationController::class, 'getStates']);
Route::get('/locations/{countryCode}/cities', [LocationController::class, 'getCities']);
Route::get('/locations/{countryCode}/{stateCode}/cities', [LocationController::class, 'getCities']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// routes/web.php atau routes/api.php
Route::get('/tracking', [TrackingController::class, 'track']);

Route::get('/states/{countryCode}', function ($countryCode) {
    $states = \App\Models\States::where('country_code', $countryCode)->get();
    return response()->json($states);
});

Route::get('/cities/{stateCode}', function ($stateCode) {
    $cities = \App\Models\Cities::where('state_code', $stateCode)->get();
    return response()->json($cities);
});

Route::get('/postalcodes/{cityName}', function ($cityName) {
    $postalCodes = \App\Models\PostalCodes::where('city_name', $cityName)->get();
    return response()->json($postalCodes);
});
