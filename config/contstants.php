<?php

return  [
    'stripe' => [
        'api_key' => env('STRIPE_API_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ]
];
