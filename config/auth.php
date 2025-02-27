
<?php

return [
    'defaults' => [
        'guard' => 'sanctum',
    ],

    'guards' => [
        'sanctum' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],
];
