<?php

return [
    'api' => [
        'enabled' => true,
        'rate_limit' => 60,
        'rate_limit_per_minute' => 5,
        'allowed_origins' => [
            'https://yourwebsite.com',
            'https://anotherwebsite.com',
        ],
        'key' => env('API_KEY'),
    ],

    'recaptcha' => [
        'enabled' => false,
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'threshold' => 0.5,
    ],
];
