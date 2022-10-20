<?php

namespace HossamMonir\HyperPay\Services;

use Exception;
use Illuminate\Http\JsonResponse;

class HyperPayFacade
{
    private array $paymentMethod;

    private string $checkoutId;

    private string $currency;

    private array $customer;

    private array $registrations;

    private float $amount;

    /**
     * @param  array  $paymentMethod
     * @return $this
     */
    public function paymentMethod(array $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @param  array  $customer
     * @return $this
     */
    public function customer(array $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @param  array  $registrations
     * @return $this
     */
    public function registrations(array $registrations): self
    {
        $this->registrations = $registrations;

        return $this;
    }

    /**
     * @param  string  $currency
     * @return $this
     */
    public function currency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param  float  $amount
     * @return $this
     */
    public function amount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param  string  $checkoutId
     * @return $this
     */
    public function checkoutId(string $checkoutId): self
    {
        $this->checkoutId = $checkoutId;

        return $this;
    }

    /**
     * Create Checkout Request.
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function checkout(): JsonResponse
    {
        return (new HyperPayPrepareCheckout($this->paymentMethod) )
            ->amount($this->amount)
            ->currency($this->currency)
            ->customer($this->customer)
            ->checkout();
    }

    /**
     * Create Checkout Request.
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function checkoutWithTokenization(): JsonResponse
    {
        return (new HyperPayPrepareCheckout($this->paymentMethod) )
            ->amount($this->amount)
            ->currency($this->currency)
            ->customer($this->customer)
            ->checkoutWithTokenization();
    }

    /**
     * Create Checkout Request.
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function oneClickCheckout(): JsonResponse
    {
        return (new HyperPayPrepareCheckout($this->paymentMethod) )
            ->amount($this->amount)
            ->currency($this->currency)
            ->customer($this->customer)
            ->registrations($this->registrations)
            ->oneClickCheckout();
    }

    /**
     * Validate Payment Status
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function validate(): JsonResponse
    {
        return (new HyperPayPaymentStatus($this->paymentMethod) )
            ->checkoutId($this->checkoutId)
            ->validate();
    }
}
