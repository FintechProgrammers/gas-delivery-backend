<?php

return  [
    'base_url' =>  env('NIJA_PAY_BASE_URL'),
    'api_key' => env('NIJA_PAY_API_KEY'),
    'secret' => env('NIJA_PAY_SECRET'),
    'sending_account' => env('NIJA_PAY_SENDING_ACCOUNT'),
    'sender_name'  => env('NIJA_PAY_SENDING_NAME'),
    'auth_key' => env("NIJA_PAY_AUTH_KEY")
];
