<?php

return [
    'env'            => env('DARAJA_ENV', 'sandbox'),
    'consumer_key'   => env('DARAJA_CONSUMER_KEY'),
    'consumer_secret'=> env('DARAJA_CONSUMER_SECRET'),
    'shortcode'      => env('DARAJA_SHORTCODE', '174379'),
    'passkey'        => env('DARAJA_PASSKEY'),
    'callback_url'   => env('DARAJA_CALLBACK_URL'),

    'base_url' => env('DARAJA_ENV', 'sandbox') === 'production'
        ? 'https://api.safaricom.co.ke'
        : 'https://sandbox.safaricom.co.ke',
];
