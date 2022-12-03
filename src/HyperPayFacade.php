<?php

namespace HossamMonir\HyperPay;

use Exception;
use HossamMonir\HyperPay\Services\PaymentStatus;
use HossamMonir\HyperPay\Services\PrepareCheckout;
use HossamMonir\HyperPay\Services\SettlementReport;
use HossamMonir\HyperPay\Services\TransactionReport;
use HossamMonir\HyperPay\Utilities\Amount;
use HossamMonir\HyperPay\Utilities\Customer;
use HossamMonir\HyperPay\Utilities\PaymentMethods;
use Illuminate\Http\JsonResponse;
use Throwable;

class HyperPayFacade
{
    private array $paymentMethod;

    private string $checkoutId;

    private string $currency;

    private array $customer;

    private array $registrations;

    private string $amount;

    private string $fromDate;

    private string $toDate;

    /**
     * @param  string  $paymentMethod
     * @return $this
     *
     * @throws Exception
     */
    public function setMethod(string $paymentMethod): self
    {
        $method = new PaymentMethods($paymentMethod);

        $this->paymentMethod = $method->toArray();

        return $this;
    }

    /**
     * @param  array  $customer
     * @return $this
     *
     * @throws Throwable
     */
    public function setCustomer(array $customer): self
    {
        $customer = new Customer($customer);

        $this->customer = $customer->toArray();

        return $this;
    }

    /**
     * Set Registrations to be used in the OneClick checkout.
     *
     * @param  array  $registrations
     * @return $this
     */
    public function setRegistrations(array $registrations): self
    {
        $this->registrations = $registrations;

        return $this;
    }

    /**
     * @param  string  $currency
     * @return $this
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param string $amount
     * @return $this
     * @throws Throwable
     */
    public function setAmount(string $amount): self
    {
        $this->amount = Amount::format($amount);

        return $this;
    }

    /**
     * Set Checkout ID.
     *
     * @param  string  $checkoutId
     * @return $this
     */
    public function setCheckoutId(string $checkoutId): self
    {
        $this->checkoutId = $checkoutId;

        return $this;
    }

    /**
     * Set From Date to ['config'].
     *
     * @param  string  $fromDate
     * @return $this
     */
    public function setFromDate(string $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Set To Date to ['config'].
     *
     * @param  string  $toDate
     * @return $this
     */
    public function setToDate(string $toDate): self
    {
        $this->toDate = $toDate;

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
        ray($this->amount)->red();
        return (new PrepareCheckout($this->paymentMethod) )
            ->setAmount($this->amount)
            ->setCurrency($this->currency)
            ->setCustomer($this->customer)
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
        return (new PrepareCheckout($this->paymentMethod) )
            ->setAmount($this->amount)
            ->setCurrency($this->currency)
            ->setCustomer($this->customer)
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
        return (new PrepareCheckout($this->paymentMethod) )
            ->setAmount($this->amount)
            ->setCurrency($this->currency)
            ->setRegistrations($this->registrations)
            ->oneClickCheckout();
    }

    /**
     * Validate Payment Status
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function getStatus(): JsonResponse
    {
        return (new PaymentStatus($this->paymentMethod) )
            ->setCheckoutId($this->checkoutId)
            ->getStatus();
    }

    /**
     * Get Transaction Report
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function getTransactionReport(): JsonResponse
    {
        return (new TransactionReport($this->paymentMethod) )
            ->setCheckoutId($this->checkoutId)
            ->getTransactionReport();
    }

    /**
     * Get Settlement Report
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function getSettlement(): JsonResponse
    {
        return (new SettlementReport($this->paymentMethod) )
            ->setFromDate($this->fromDate)
            ->setToDate($this->toDate)
            ->getSettlement();
    }
}
