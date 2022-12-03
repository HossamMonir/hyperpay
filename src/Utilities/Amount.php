<?php

namespace HossamMonir\HyperPay\Utilities;


class Amount
{
    /**
     * Convert Amount to decimals.
     */
    public static function format(string $amount): string
    {
        return number_format($amount, 2, '.', '');
    }
}
