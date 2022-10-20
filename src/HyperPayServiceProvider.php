<?php

namespace HossamMonir\HyperPay;

use HossamMonir\HyperPay\Services\HyperPayFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class HyperPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register a class in the service container
        $this->app->bind('HyperPay', function () {
            return new HyperPayFacade();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //h HyperPay services config to the application config
        $this->publishes([
            __DIR__.'/config/hyperpay.php' => config_path('hyperpay.php'),
        ]);

        $loader = AliasLoader::getInstance();
        $loader->alias('HyperPay', 'HossamMonir\\HyperPay\\Facades\\HyperPay');

        // ... other things
        $this->loadViewsFrom(__DIR__.'/../resources/js', 'hyperpay');


        $color = config('hyperpay.config.color');
        $styles = "
        <style type='text/tailwindcss'>
            @layer components {
               .ltr-grid {
                    direction: ltr;
                }
                .wpwl-form {
                    @apply text-sm bg-gray-50 p-5 shadow-none border-2 border-gray-200 rounded-lg text-gray-600 justify-center items-start
                }
                .wpwl-control-brand {
                    @apply hidden
                }
                .wpwl-button-pay {
                    @apply text-sm font-bold bg-$color-700 hover:bg-$color-600 rounded-md py-2 text-white border-0
                }
                .wpwl-control-cardNumber {
                    @apply text-sm border-2 ltr-grid h-10 text-right border-gray-200 focus:border-$color-400 focus:ring-1 focus:ring-$color-400 rounded-lg
                }
                .wpwl-control-cardHolder {
                    @apply text-sm border-gray-200 h-10 focus:border-$color-400 focus:ring-1 focus:ring-$color-400 rounded-lg
                }
                .wpwl-control-cvv {
                    @apply text-sm border-2 h-10 border-gray-200 focus:border-$color-400 focus:ring-1 focus:ring-$color-400 rounded-lg
                }
                .wpwl-control-expiry {
                    @apply text-sm h-10 border-gray-200 focus:border-$color-400 focus:ring-1 focus:ring-$color-400 rounded-lg
                }

                .wpwl-group-button {
                    @apply flex justify-center items-center content-center text-center
                }
            }
            .wpwl-apple-pay-button {
                 -webkit-appearance: -apple-pay-button !important;
            }
        </style>
        ";
        $hyperPayOptionsScript = config('hyperpay.config.payment_status');
        Blade::directive('HyperPayScript', function (string $scriptUrl) use ($styles) {
            $url = json_decode($scriptUrl);
            $scripts = "<script async src='$url'></script>";
//            $scripts .= "<script src='http://127.0.0.1:8000/hyperpay.js'></script>";
            $scripts .= "<script src='https://cdn.tailwindcss.com'></script>";
            $scripts .= $styles;

            return $scripts;
        });

        $hyperPayPaymentStatus = config('hyperpay.config.payment_status');
        Blade::directive('HyperPayForm', function (string $paymentMethod) use ($hyperPayPaymentStatus) {
            $method = json_decode($paymentMethod);
            return "<form action='$hyperPayPaymentStatus' class='paymentWidgets' data-brands='$method' />";
        });
    }
}
