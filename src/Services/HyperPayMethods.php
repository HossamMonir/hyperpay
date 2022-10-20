<?php

namespace HossamMonir\HyperPay\Services;

class HyperPayMethods
{
    /**
     * Define Visa Card Pay Method
     *
     * @return array
     */
    public static function VISA(): array
    {
        return ['payment_method' => 'VISA'];
    }

    /**
     * Define Master Card Pay Method
     *
     * @return array
     */
    public static function MASTER(): array
    {
        return ['payment_method' => 'MASTER'];
    }

    /**
     * Define Mada Card Method
     *
     * @return array
     */
    public static function MADA(): array
    {
        return ['payment_method' => 'MADA'];
    }

    /**
     * Define Apple Pay Method
     *
     * @return array
     */
    public static function APPLEPAY(): array
    {
        return ['payment_method' => 'APPLEPAY'];
    }

    /**
     * Define Google Pay Method
     *
     * @return array
     */
    public static function GOOGLEPAY(): array
    {
        return ['payment_method' => 'GOOGLEPAY'];
    }
}
