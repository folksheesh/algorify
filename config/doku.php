<?php
/*
    |--------------------------------------------------------------------------
    | DOKU Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for DOKU payment gateway integration
    |
    */
    
return [
    'client_id'     => env('DOKU_CLIENT_ID'),
    'secret_key'    => env('DOKU_SECRET_KEY'),
    'is_production' => env('DOKU_IS_PRODUCTION', false),
    'base_url'      => env('DOKU_IS_PRODUCTION', false)
        ? 'https://api.doku.com'
        : 'https://api-sandbox.doku.com',
    'disable_ssl_verify' => env('DOKU_DISABLE_SSL_VERIFY', false),
];