<?php

return  [
    'stripe' => [
        'api_key' => env('STRIPE_API_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'twilio' => [
        'sid' => env('TWILIO_AUTH_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM')
    ],
    'flutterwave' => [
        'base_url' => env('FLUTTERWAVE_BASE_URL'),
        'public_key' => env('FLUTTERWAVE_API_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'webhook_secret' => env('FLUTTERWAVE_WEBHOOK_SECRET'),
        'redirect_url' => env('FLUTTERWAVE_REDIRECT_URL'),
    ],
    'verifyme' => [
        'base_url' => env('VERIFY_ME_BASE_URL'),
        'api_key' => env('VERIFY_ME_API_KEY'),
        'secret' => env('VERIFY_ME_SECRET'),
    ]
];
