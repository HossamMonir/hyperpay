<?php

namespace HossamMonir\HyperPay\Interfaces;

use Illuminate\Http\JsonResponse;

interface PaymentReportInterface
{
    public function setMethod(string $paymentMethod): static;

    public function setCheckoutId(string $checkoutId): static;

    public function getTransactionReport(): JsonResponse;
}
