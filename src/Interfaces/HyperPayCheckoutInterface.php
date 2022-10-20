<?php

namespace HossamMonir\HyperPay\Interfaces;

use Illuminate\Http\JsonResponse;

interface HyperPayCheckoutInterface
{
    public function paymentMethod(string $paymentMethod): static;

    public function currency(string $currency = null): static;

    public function amount(float $amount): static;

    public function customer(array $customer): static;

    public function checkout(): JsonResponse;
}
