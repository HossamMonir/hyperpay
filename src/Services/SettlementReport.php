<?php

namespace HossamMonir\HyperPay\Services;

use Exception;
use HossamMonir\HyperPay\Contracts\HyperPay;
use HossamMonir\HyperPay\Interfaces\SettlementReportInterface;
use HossamMonir\HyperPay\Traits\Processor;
use Illuminate\Http\JsonResponse;

class SettlementReport extends HyperPay implements SettlementReportInterface
{
    use Processor;

    /**
     * Initialize API Processor Constructor.
     * Accept All HyperPay API Parameters.
     *
     * @throws Exception
     */
    public function __construct(array $config)
    {
        // Call parent constructor to initialize common settings
        parent::__construct($config);
    }

    /**
     * Set Payment Method.
     *
     * @param  string  $paymentMethod
     * @return $this
     */
    public function setMethod(string $paymentMethod): static
    {
        $this->config['payment_method'] = $paymentMethod;

        return $this;
    }

    /**
     * Set From Date
     *
     * @param  string  $fromDate
     * @return $this
     */
    public function setFromDate(string $fromDate): static
    {
        $this->config['date.from'] = $fromDate;

        return $this;
    }

    /**
     * Set To Date
     *
     * @param  string  $toDate
     * @return $this
     */
    public function setToDate(string $toDate): static
    {
        $this->config['date.to'] = $toDate;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getSettlement(): JsonResponse
    {
        return response()->json($this->initiate());
    }

    /**
     * Initiate Settlement Report.
     *
     * @return array
     *
     * @throws Exception
     */
    private function initiate(): array
    {
        return $this->render($this->SettlementReport());
    }

    /**
     * Render HyperPay API Response.
     *
     * @param  array  $response
     * @return array
     */
    private function render(array $response): array
    {
        return [
            'response' => $response,
            'props' => [
                'payment_method' => $this->config['payment_method'],
                'test_mode' => $this->isTestMode,
                'status' => [
                    'success' => $this->validateStatus($response['result']['code']),
                    'message' => $response['result']['description'],
                ],
            ],
        ];
    }
}
