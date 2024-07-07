<?php
return [
    'routes' => [
        'banks' => 'banks',
        'resolve-account-numbers' => 'resolve-account-numbers',
    ],
    'default' => 'paystack',
    'processors' => [
        'flutterwave' => [
            'base_uri' => env('FLUTTERWAVE_BASE_URI', 'https://api.flutterwave.com/v3'),
            'secret' => env('FLUTTERWAVE_SECRET'),
            'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
            'redirect_url' => env('FLUTTERWAVE_REDIRECT_URL'),
            'cost' => 1.0, // cost per transaction in percentage
            'reliability' => 70, // reliability in percentage
            'currencies' => [
                'NGN'   => 'Nigerian Naira',
                'USD'   => 'US Dollar',
                'GHS'   => 'Ghanaian Cedi',
                'ZAR'   => 'South African Rand',
                'KES'   => 'Kenyan Shilling'
            ]
        ],
        'paystack' => [
            'base_uri' => env('PAYSTACK_BASE_URI', 'https://api.paystack.co'),
            'secret' => env('PAYSTACK_SECRET'),
            'redirect_url' => env('PAYSTACK_REDIRECT_URL'),
            'cost' => 1.5, // cost per transaction in percentage
            'reliability' => 85, // reliability in percentage
            'currencies' => [
                'NGN'   => 'Nigerian Naira',
            ]
        ]
    ],
    'routing_rules' => [
        'prioritize' => 'cost',
        'thresholds' => [
            'cost' => 2.0, // maximum acceptable cost percentage
            'reliability' => 90, // minimum acceptable reliability percentage
        ],
    ],
];