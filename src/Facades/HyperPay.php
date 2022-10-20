<?php

namespace HossamMonir\HyperPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static paymentMethod(array $paymentMethod)
 * @method static amount(float $amount)
 * @method static customer(array $customer)
 * @method static checkout()
 * @method static checkoutId(string $checkoutId)
 * @method static validate()
 */
class HyperPay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'HyperPay';
    }
}
