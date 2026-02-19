<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'fedex' => [
        'mode' => env('FEDEX_MODE', 'sandbox'),
        'sandbox_url' => env('FEDEX_SANDBOX_URL'),
        'live_url' => env('FEDEX_LIVE_URL'),
        'client_id' => env('FEDEX_CLIENT_ID'),
        'client_secret' => env('FEDEX_CLIENT_SECRET'),
        'account_number' => env('FEDEX_ACCOUNT_NUMBER'),
        'shipper' => [
            'name' => env('FEDEX_SHIPPER_NAME'),
            'email' => env('FEDEX_SHIPPER_EMAIL'),
            'phone' => env('FEDEX_SHIPPER_PHONE'),
            'company' => env('FEDEX_SHIPPER_COMPANY'),
            'address' => env('FEDEX_SHIPPER_ADDRESS'),
            'city' => env('FEDEX_SHIPPER_CITY'),
            'state' => env('FEDEX_SHIPPER_STATE'),
            'postal_code' => env('FEDEX_SHIPPER_POSTAL'),
            'country_code' => env('FEDEX_SHIPPER_COUNTRY'),
        ],
    ],

    'lionparcel' => [
        'api_url' => env('LION_PARCEL_API_URL'),
        'api_key' => env('LION_PARCEL_API_KEY'),
        'basic_auth' => env('LION_BASIC_AUTH'),
        'shipper' => [
            'name' => env('LION_SHIPPER_NAME'),
            'email' => env('LION_SHIPPER_EMAIL'),
            'phone' => env('LION_SHIPPER_PHONE'),
            'company' => env('LION_SHIPPER_COMPANY'),
            'address' => env('LION_SHIPPER_ADDRESS'),
            'city' => env('LION_SHIPPER_CITY'),
            'state' => env('LION_SHIPPER_STATE'),
            'postal_code' => env('SHIPPER_POSTAL_CODE'),
            'country_code' => env('SHIPPER_COUNTRY_CODE'),
            'origin' => env('LION_SHIPPER_ORIGIN'),
        ],
    ],

];
