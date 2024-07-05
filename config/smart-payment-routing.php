<?php
return [
    'route'    => 'hello-route',
    'default' => 'paystack',
    'processors' => [
        'paystack' => [
            'key' => env('PAYSTACK_KEY'),
            'secret' => env('PAYSTACK_SECRET'),
        ],
        'stripe' => [
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
        ],
    ],
    'routing_rules' => [
        // Customizable rules
    ],
];