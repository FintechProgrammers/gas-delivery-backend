<?php

return [
    'base_url' => env('PAYSTACK_BASE_URL'),
    'public_key' => env('PAYSTACK_PUBLIC_KEY'),
    'secret_key' => env('PAYSTACK_SECRET_KEY'),
    'preferred_bank' => env('PAYSTACK_ACCOUNT_PROVIDER')
];
