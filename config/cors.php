<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['POST'],
    'allowed_origins' => array_merge(
        explode(',', env('ALLOWED_ORIGINS', '')),
        config('contact.api.allowed_origins')
    ),
    'allowed_headers' => ['Content-Type', 'X-API-KEY', 'Origin'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
