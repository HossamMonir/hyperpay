<?php

namespace HossamMonir\HyperPay\Services;

use Exception;
use HossamMonir\HyperPay\Contracts\HyperPay;
use HossamMonir\HyperPay\Interfaces\CheckoutInterface;
use HossamMonir\HyperPay\Traits\Processor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class PrepareCheckout extends HyperPay implements CheckoutInterface
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
     * Set Currency
     *
     * @param  string|null  $currency
     * @return $this
     */
    public function setCurrency(string $currency = null): static
    {
        $this->config['currency'] = $currency;

        return $this;
    }

    /**
     * Set Amount
     *
     * @param  string  $amount
     * @return $this
     */
    public function setAmount(string $amount): static
    {
        $this->config['amount'] = $amount;

        return $this;
    }

    /**
     * Set Customer Information.
     *
     * @param array|null $customer
     * @return $this
     */
    public function setCustomer(array $customer = null): static
    {
        $this->config['customer'] = $customer;

        return $this;
    }

    /**
     * Set Registrations IDs ['config'] to be used in the checkout.
     *
     * @param  array  $registrations
     * @return $this
     */
    public function setRegistrations(array $registrations): static
    {
        Arr::map($registrations, function ($value, $key) {
            return $this->config['registrations'.'['.$key.']']['id'] = $value;
        });

        return $this;
    }

    /**
     * Return Checkout Tokenized Response.
     *
     * @return JsonResponse
     */
    public function checkoutWithTokenization(): JsonResponse
    {
        $this->withTokenization();

        return response()->json($this->initiate());
    }

    /**
     * Return One Click Checkout Response.
     *
     * @return JsonResponse
     */
    public function oneClickCheckout(): JsonResponse
    {
        $this->withOneClick();

        return response()->json($this->initiate());
    }

    /**
     * Submit Checkout Request to HyperPay API.
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function checkout(): JsonResponse
    {
        $this->withBasic();

        return response()->json($this->initiate());
    }

    /**
     * Initiate Checkout Request.
     *
     * @return array
     */
    private function initiate(): array
    {
        return $this->render($this->PrepareCheckout());
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
                'merchant_transaction_id' => $this->config['merchantTransactionId'],
                'payment_method' => $this->config['payment_method'],
                'test_mode' => $this->isTestMode,
                'endpoint' => $this->endpoint,
                'script_url' => isset($response['id']) ? $this->endpoint.'/v1/paymentWidgets.js?checkoutId='.$response['id'] : null,
                'currency' => $this->config['currency'],
                'amount' => $this->config['amount'],
                'status' => [
                    'success' => $this->validateCheckout($response['result']['code']),
                    'message' => $response['result']['description'],
                ],
            ],
        ];
    }

    /**
     * Tokenization Configuration.
     *
     * @return void
     */
    protected function withTokenization(): void
    {
        $this->config['createRegistration'] = true;
        $this->config['standingInstruction'] = [
            'source' => 'CIT',
            'mode' => 'INITIAL',
        ];
    }

    /**
     * Basic Checkout Configuration.
     *
     * @return void
     */
    protected function withBasic(): void
    {
        $this->config['paymentType'] = 'DB';
    }

    /**
     * One Click Configuration.
     *
     * @return void
     */
    protected function withOneClick(): void
    {
        $this->config['standingInstruction'] = [
            'source' => 'CIT',
            'mode' => 'INITIAL',
            'type' => 'UNSCHEDULED',
        ];
    }
}
