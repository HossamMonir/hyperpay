<?php

namespace HossamMonir\HyperPay\Traits;

use Illuminate\Support\Facades\Http;

trait Processor
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
    public function TransactionReport(string $checkoutId): array
    {
        return Http::baseUrl($this->endpoint)
            ->withToken($this->accessToken)
            ->withOptions(['verify' => ! $this->isTestMode == true])
            ->get("v1/query/$checkoutId", $this->paymentReportMappingData())
            ->json();
    }

    /**
     * HyperPay Settlement Report Query API Request.
     *
     * @return array
     */
    public function SettlementReport(): array
    {
        return Http::baseUrl($this->endpoint)
            ->withToken($this->accessToken)
            ->withOptions(['verify' => ! $this->isTestMode == true])
            ->get('reports/v1/reconciliations/aggregations', $this->settlementReportMappingData())
            ->json();
    }
}
