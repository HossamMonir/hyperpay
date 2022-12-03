<?php

namespace HossamMonir\HyperPay\Utilities;

use Exception;
use HossamMonir\HyperPay\Exceptions\InvalidCustomer;
use Illuminate\Support\Arr;
use Throwable;

class Customer
{
    private array $customer;

    /**
     * @throws Throwable
     */
    public function __construct(array $customer)
    {
        $this->customer = $customer;

        throw_if(
            ! Arr::has($this->customer, ['firstName', 'lastName', 'email', 'mobile']),
            new InvalidCustomer('Invalid Customer Data.')
        );
    }

    public function toArray(): array
    {
        return [
            'givenName' => $this->customer['firstName'],
            'surname' => $this->customer['lastName'],
            'email' => $this->customer['email'],
            'mobile' => $this->customer['mobile'],
        ];
    }
}
