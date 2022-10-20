<?php

namespace HossamMonir\HyperPay\Services;

use Exception;
use HossamMonir\HyperPay\Contracts\HyperPay;
use HossamMonir\HyperPay\Interfaces\HyperPayPaymentReportInterface;
use HossamMonir\HyperPay\Traits\HyperPayAPIRequest;
use Illuminate\Http\JsonResponse;

class HyperPayPaymentReport extends HyperPay implements HyperPayPaymentReportInterface
{
    use HyperPayAPIRequest;

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
     * @param  string  $paymentMethod
     * @return $this
     */
    public function paymentMethod(string $paymentMethod): static
    {
        $this->config['payment_method'] = $paymentMethod;

        return $this;
    }

    /**
     * @param  string  $checkoutId
     * @return $this
     */
    public function checkoutId(string $checkoutId): static
    {
        $this->config['checkout_id'] = $checkoutId;

        return $this;
    }

    /**
     * Submit Payment Status Request to HyperPay API.
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function validate(): JsonResponse
    {
        $response = $this->PaymentReport($this->config['checkout_id']);
        return response()->json([
            'response' => [
                $response,
            ],
            'config' => [
                'payment_method' => $this->config['payment_method'],
                'test_mode' => $this->isTestMode,
                'status' => $this->validateStatus($response['result']['code']),
                'status_message' => $this->validateStatus($response['result']['code'])? 'transaction success' : 'transaction declined',
            ],

        ]);
    }
}
