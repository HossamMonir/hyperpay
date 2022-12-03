<?php

namespace HossamMonir\HyperPay\Utilities;

use HossamMonir\HyperPay\Exceptions\InvalidAmount;
use Throwable;

class Amount
{
    /**
     * Validate Amount.
     *
     * @throws Throwable
     */
    public static function validate(string $amount): float
    {
        throw_if(
            ! preg_match('/[0-9]{1,10}(\.[0-9]{2})/', $amount),
            new InvalidAmount('Invalid Amount format.')
        );

        return $amount;
    }
}
