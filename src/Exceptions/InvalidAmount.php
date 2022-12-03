<?php

namespace HossamMonir\HyperPay\Exceptions;

use Exception;
use Illuminate\Http\Response;

class InvalidAmount extends Exception
{
    public function render(): Response
    {
        return response([
            'error' => $this->getMessage(),
            'help' => 'Amount format should be like : 90.00'], 400);
    }
}
