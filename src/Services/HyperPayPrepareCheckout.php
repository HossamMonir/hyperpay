<?php

namespace HossamMonir\HyperPay\Services;

use Exception;
use HossamMonir\HyperPay\Contracts\HyperPay;
use HossamMonir\HyperPay\Interfaces\HyperPayCheckoutInterface;
use HossamMonir\HyperPay\Traits\HyperPayAPIRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class HyperPayPrepareCheckout extends HyperPay implements HyperPayCheckoutInterface
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
     * @param string $paymentMethod
     * @return $this
     */
    public function paymentMethod(string $paymentMethod): static
    {
        $this->config['payment_method'] = $paymentMethod;

        return $this;
    }

    /**
     * @param string|null $currency
     * @return $this
     */
    public function currency(string $currency = null): static
    {
        $this->config['currency'] = $currency;

        return $this;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function amount(float $amount): static
    {
        $this->config['amount'] = $amount;

        return $this;
    }

    /**
     * @param array $customer
     * @return $this
     */
    public function customer(array $customer): static
    {
        $this->config['customer'] = $customer;

        return $this;
    }

    /**
     * @param array $registrations
     * @return $this
     */
    public function registrations(array $registrations): static
    {

        Arr::map($registrations, function ($value, $key) {
            return $this->config['registrations'.'['.$key.']']['id'] = $value;
        });

        return $this;
    }

    private function withTokenization(): void
    {
        $this->config['createRegistration'] = true;
        $this->config['standingInstruction'] = [
            'source' => 'CIT',
            'mode' => 'INITIAL',
        ];
    }

    private function withBasic()
    {
        $this->config['paymentType'] = 'DB';
    }

    private function withOneClick()
    {
        $this->config['standingInstruction'] = [
            'source' => 'CIT',
            'mode' => 'INITIAL',
            'type' => 'UNSCHEDULED',
        ];
    }


    public function checkoutWithTokenization(): JsonResponse
    {
        $this->withTokenization();

        return response()->json([
            $this->initiateCheckout()
        ]);
    }


    public function oneClickCheckout(): JsonResponse
    {
        $this->withOneClick();

        return response()->json([
            $this->initiateCheckout()
        ]);
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

        return response()->json([
            $this->initiateCheckout()
        ]);
    }

    /**
     * @return array
     */
    private function initiateCheckout(): array
    {
        return [
            'response' => $this->PrepareCheckout(),
            'config' => [
                'merchant_transaction_id' => $this->config['merchantTransactionId'],
                'payment_method' => $this->config['payment_method'],
                'test_mode' => $this->isTestMode,
                'endpoint' => $this->endpoint,
                'script_url' => $this->endpoint . '/v1/paymentWidgets.js?checkoutId=' . $this->PrepareCheckout()['id'],
                'currency' => $this->config['currency'],
                'customer' => $this->config['customer'],
                'amount' => $this->config['amount'],
            ],
        ];
    }
}
