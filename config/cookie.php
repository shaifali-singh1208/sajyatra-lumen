<?php

return [
    'path' => '/',
    'domain' => env('COOKIE_DOMAIN', null),
    'secure' => env('COOKIE_SECURE', false),
    'http_only' => true,
    'same_site' => 'lax',
];
