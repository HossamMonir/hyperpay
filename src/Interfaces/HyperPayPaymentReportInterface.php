<?php

namespace HossamMonir\HyperPay\Interfaces;

use Illuminate\Http\JsonResponse;

interface HyperPayPaymentReportInterface
{
    public function paymentMethod(string $paymentMethod): static;

    public function checkoutId(string $checkoutId): static;

    public function validate(): JsonResponse;
}
