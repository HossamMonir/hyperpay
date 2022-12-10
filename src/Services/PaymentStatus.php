<?php

namespace HossamMonir\HyperPay\Services;

use Exception;
use HossamMonir\HyperPay\Contracts\HyperPay;
use HossamMonir\HyperPay\Interfaces\PaymentStatusInterface;
use HossamMonir\HyperPay\Traits\Processor;
use Illuminate\Http\JsonResponse;

class PaymentStatus extends HyperPay implements PaymentStatusInterface
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
     * Set Payment Method to ['config'].
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
     * Set Checkout ID to ['config'].
     *
     * @param  string  $checkoutId
     * @return $this
     */
    public function setCheckoutId(string $checkoutId): static
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
    public function getStatus(): JsonResponse
    {
        return response()->json($this->initiate());
    }

    /**
     * Initiate Payment Status Request.
     *
     * @return array
     */
    private function initiate(): array
    {
        return $this->render($this->PaymentStatus($this->config['checkout_id']));
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
