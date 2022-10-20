<?php

namespace HossamMonir\HyperPay\Traits;

use Illuminate\Support\Facades\Http;

trait HyperPayAPIRequest
{
    /**
     * HyperPay Checkout API Request.
     */
    public function PrepareCheckout()
    {
        return Http::baseUrl($this->endpoint)
            ->withToken($this->accessToken)
            ->asForm()
            ->withOptions(['verify' => ! $this->isTestMode == true])
            ->post('v1/checkouts', $this->checkoutMappingData())
            ->json();
    }

    /**
     * HyperPay Payment Status API Request.
     *
     * @param  string  $checkoutId
     * @return array
     */
    public function PaymentStatus(string $checkoutId): array
    {
        return Http::baseUrl($this->endpoint)
            ->withToken($this->accessToken)
            ->withOptions(['verify' => ! $this->isTestMode == true])
            ->get("v1/checkouts/$checkoutId/payment", $this->paymentStatusMappingData())
            ->json();
    }

    /**
     * HyperPay Payment Report Query API Request.
     *
     * @param  string  $checkoutId
     * @return array
     */
    public function PaymentReport(string $checkoutId): array
    {
        return Http::baseUrl($this->endpoint)
            ->withToken($this->accessToken)
            ->withOptions(['verify' => ! $this->isTestMode == true])
            ->get("v1/query/$checkoutId", $this->paymentReportMappingData())
            ->json();
    }
}
