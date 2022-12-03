<?php

namespace HossamMonir\HyperPay\Interfaces;

use Illuminate\Http\JsonResponse;

interface CheckoutInterface
{
    public function setMethod(string $paymentMethod): static;

    public function setCurrency(string $currency = null): static;

    public function setAmount(string $amount): static;

    public function setCustomer(array $customer = null): static;

    public function setRegistrations(array $registrations): static;

    public function checkoutWithTokenization(): JsonResponse;

    public function oneClickCheckout(): JsonResponse;

    public function checkout(): JsonResponse;
}
