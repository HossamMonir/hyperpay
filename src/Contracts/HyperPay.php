<?php

namespace HossamMonir\HyperPay\Contracts;

use Exception;
use HossamMonir\HyperPay\Exceptions\InvalidPaymentMethod;
use Illuminate\Support\Arr;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

abstract class HyperPay
{
    /**
     * Configurations array
     *
     * @var array
     */
    protected array $config;

    /**
     * HyperPay endpoint ( Live || Test )
     *
     * @var string
     */
    protected string $endpoint;

    /**
     * HyperPay Access Token
     *
     * @var string
     */
    protected string $accessToken;

    /**
     * Bind Environment Mode ( Live || Test )
     *
     * @var bool
     */
    protected bool $isTestMode;

    /**
     * HyperPay Processor Constructor.
     *
     * @param $config
     *
     * @throws Exception
     */
    public function __construct($config)
    {
        $this->setHyperPayConfig($config);
    }

    /**
     * Switch between Live and Test Mode.
     *
     * @return bool
     */
    protected function setEnvironment(): bool
    {
        return (bool) config('hyperpay.config.test_mode') === true ? $this->isTestMode = true : $this->isTestMode = false;
    }

    /**
     * Set HyperPay Configurations.
     *
     * @throws Exception
     */
    protected function setHyperPayConfig(array $config): void
    {
        // Set HyperPay Environment
        $this->setEnvironment();

        // Set HyperPay Endpoint
        $this->endpoint = $this->isTestMode ? 'https://eu-test.oppwa.com' : 'https://oppwa.com';

        // Set HyperPay Access Token
        $this->accessToken = $this->isTestMode ? config('hyperpay.config.test.access_token') : config('hyperpay.config.live.access_token');

        // Set Base Configurations
        $config['currency'] = $config['currency'] ?? config('hyperpay.config.currency');
        $config['paymentType'] = config('hyperpay.config.payment_type');
        $config['merchantTransactionId'] = $this->generateTransactionId();

        // Set Company Details
        $config['billing'] = [
            'street1' => config('hyperpay.config.company.street1'),
            'street2' => config('hyperpay.config.company.street2'),
            'city' => config('hyperpay.config.company.city'),
            'state' => config('hyperpay.config.company.state'),
            'postcode' => config('hyperpay.config.company.postcode'),
            'country' => config('hyperpay.config.company.country'),
        ];

        // Set Default HyperPay Configurations
        $this->config = $config;

        // Set HyperPay Entity
        $this->config['entityId'] = $this->getHyperPayEntity();
    }

    /**
     * Get Target HyperPay Entity
     *
     * @throws Exception
     */
    protected function getHyperPayEntity(): string
    {
        // HyperPay Test Mode Entity
        $testEntity = match ($this->config['payment_method']) {
            'VISA' => config('hyperpay.config.test.visa'),
            'MASTER' => config('hyperpay.config.test.master_card'),
            'MADA' => config('hyperpay.config.test.mada'),
            'APPLEPAY' => config('hyperpay.config.test.apple_pay'),
            'GOOGLEPAY' => config('hyperpay.config.test.google_pay'),
            default => throw new InvalidPaymentMethod('Payment Method Not Found')
        };

        // HyperPay Live Mode Entity
        $liveEntity = match ($this->config['payment_method']) {
            'VISA' => config('hyperpay.config.live.visa'),
            'MASTER' => config('hyperpay.config.live.master_card'),
            'MADA' => config('hyperpay.config.live.mada'),
            'APPLEPAY' => config('hyperpay.config.live.apple_pay'),
            'GOOGLEPAY' => config('hyperpay.config.live.google_pay'),
            default => throw new InvalidPaymentMethod('Payment Method Not Found')
        };

        return $this->isTestMode ? $testEntity : $liveEntity;
    }

    /**
     * Render Config to HyperPay Configurations Recursive Array.
     *
     * @param  array  $config
     * @return array
     */
    private function arrayRecursive(array $config): array
    {
        $config = new RecursiveIteratorIterator(new RecursiveArrayIterator($config));
        $result = [];
        foreach ($config as $values) {
            $keys = [];
            foreach (range(0, $config->getDepth()) as $depth) {
                $keys[] = $config->getSubIterator($depth)->key();
            }
            $result[implode('.', $keys)] = $values;
        }

        return $result;
    }

    /**
     * Get Checkout Mapped configurations.
     *
     * @return array
     */
    public function checkoutMappingData(): array
    {
        $config = $this->arrayRecursive($this->config);

        // unset Payment Method
        Arr::forget($config, 'payment_method');

        return $config;
    }

    /**
     * Render Config to HyperPay Status Array.
     *
     * @return array
     */
    public function paymentStatusMappingData(): array
    {
        return ['entityId' => $this->config['entityId']];
    }

    /**
     * Render Config to HyperPay Report Array.
     *
     * @return array
     */
    public function paymentReportMappingData(): array
    {
        return ['entityId' => $this->config['entityId']];
    }

    /**
     * Render Config to HyperPay Settlement Array.
     *
     * @return array
     */
    public function settlementReportMappingData(): array
    {
        // Set HyperPay Settlement Report Dummy Data
        if ($this->isTestMode) {
            $this->config['testMode'] = 'INTERNAL';
        }

        $config = self::arrayRecursive($this->config);

        return Arr::only($config, ['entityId', 'date.from', 'date.to', 'currency', 'testMode']);
    }

    /**
     * Generate Transaction ID
     *
     * @return string
     */
    private function generateTransactionId(): string
    {
        return md5(microtime());
    }

    /**
     * Validate HyperPay Status.
     *
     * @param  string  $resultCode
     * @return bool
     */
    protected function validateStatus(string $resultCode): bool
    {
        $successCodePattern = '/^(000\.000\.|000\.100\.1|000\.[36])/';
        $successManualReviewCodePattern = '/^(000\.400\.0|000\.400\.100)/';

        return preg_match($successCodePattern, $resultCode) || preg_match($successManualReviewCodePattern, $resultCode);
    }

    /**
     * Validate Checkout Status
     * to ensure that checkout form has been created.
     */
    protected function validateCheckout(string $resultCode): bool
    {
        return preg_match('/^(000\.200)/', $resultCode);
    }
}
