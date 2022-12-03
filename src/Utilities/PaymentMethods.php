<?php

namespace HossamMonir\HyperPay\Utilities;

use Exception;
use HossamMonir\HyperPay\Exceptions\InvalidPaymentMethod;

class PaymentMethods
{
    public function __construct(public readonly string $method)
    {}

    /**
     * @throws Exception
     */
    public function toArray(): array
    {
        return match ($this->method) {
            'VISA' => ['payment_method' => 'VISA'],
            'MASTER' => ['payment_method' => 'MASTER'],
            'MADA' => ['payment_method' => 'MADA'],
            'APPLEPAY' => ['payment_method' => 'APPLEPAY'],
            'GOOGLEPAY' => ['payment_method' => 'GOOGLEPAY'],
            default => throw new InvalidPaymentMethod('Invalid payment method, please check the documentation.'),
        };
    }
}
