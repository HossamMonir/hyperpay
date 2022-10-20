<?php

namespace HossamMonir\HyperPay\Services;

class HyperPayMethods
{
    /**
     * Define Visa Card Pay Method
     *
     * @return array
     */
    public function visa(): array
    {
        return ['payment_method' => 'VISA'];
    }

    /**
     * Define Master Card Pay Method
     *
     * @return array
     */
    public function master(): array
    {
        return ['payment_method' => 'MASTER'];
    }

    /**
     * Define Mada Card Method
     *
     * @return array
     */
    public function mada(): array
    {
        return ['payment_method' => 'MADA'];
    }

    /**
     * Define Apple Pay Method
     *
     * @return array
     */
    public function applepay(): array
    {
        return ['payment_method' => 'APPLEPAY'];
    }

    /**
     * Define Google Pay Method
     *
     * @return array
     */
    public function googlepay(): array
    {
        return ['payment_method' => 'GOOGLEPAY'];
    }
}
