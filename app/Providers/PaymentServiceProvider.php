<?php

namespace App\Providers;

use App\Services\AsaasPaymentService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    $this->app->singleton(AsaasPaymentService::class, function ()
    {
      return new AsaasPaymentService(config('payment.asaas.api-key'), config('payment.asaas.wallet-id'), config('payment.asaas.api-base-url'));
    });
  }
}
