<?php

return [

    // HyperPay Configuration Values
    'config' => [
        'test_mode' => env('HYPERPAY_TEST_MODE'),
        'currency' => env('HYPERPAY_CURRENCY'),
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
    ],
];
