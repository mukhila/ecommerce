<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Razorpay API Key
    |--------------------------------------------------------------------------
    |
    | Your Razorpay API Key ID. This is used to authenticate API requests.
    | Get your keys from Razorpay Dashboard: https://dashboard.razorpay.com/
    |
    */

    'key_id' => env('RAZORPAY_KEY_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Razorpay API Secret
    |--------------------------------------------------------------------------
    |
    | Your Razorpay API Secret. Keep this secure and never commit to version
    | control or expose publicly.
    |
    */

    'key_secret' => env('RAZORPAY_KEY_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | The currency for all Razorpay transactions. Default is INR (Indian Rupee).
    |
    */

    'currency' => 'INR',

    /*
    |--------------------------------------------------------------------------
    | Payment Receipt Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix for payment receipts. Will be combined with order number.
    |
    */

    'receipt_prefix' => 'order_rcptid_',

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    |
    | Secret used to verify Razorpay webhook signatures. Configure this in
    | your Razorpay Dashboard webhook settings.
    |
    */

    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET', ''),

];
