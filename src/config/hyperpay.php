<?php

return [

    // HyperPay Configuration Values
    'config' => [
        'test_mode' => env('HYPERPAY_TEST_MODE'),
        'currency' => env('HYPERPAY_CURRENCY'),
        'payment_type' => env('HYPERPAY_PAYMENT_TYPE'),
        'payment_status' => 'http://127.0.0.1:8000/payment-status',
        'color' => 'blue',

        'live' => [
            'access_token' => env('HYPERPAY_LIVE_ACCESS_TOKEN'),
            'visa' => env('HYPERPAY_LIVE_VISA_ENTITY'),
            'master_card' => env('HYPERPAY_LIVE_MASTERCARD_ENTITY'),
            'mada' => env('HYPERPAY_LIVE_MADA_ENTITY'),
            'apple_pay' => env('HYPERPAY_LIVE_APPLEPAY_ENTITY'),
        ],

        'test' => [
            'access_token' => env('HYPERPAY_TEST_ACCESS_TOKEN'),
            'visa' => env('HYPERPAY_TEST_VISA_ENTITY'),
            'master_card' => env('HYPERPAY_TEST_MASTERCARD_ENTITY'),
            'mada' => env('HYPERPAY_TEST_MADA_ENTITY'),
            'apple_pay' => env('HYPERPAY_TEST_APPLEPAY_ENTITY'),
        ],

        'company' => [
            'company_name' => 'Company Name',
            'street1' => 'Address 1',
            'street2' => 'Address 2',
            'city' => 'Riyadh',
            'state' => 'RUH',
            'postcode' => '13243',
            'country' => 'SA',
        ],
    ],
];
