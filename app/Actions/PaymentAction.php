<?php

namespace App\Actions;

use App\DataTransferObjects\PaymentDto;
use App\Models\Payment;
use App\Services\AsaasPaymentService;

class PaymentAction
{
    public function execute(PaymentDto $paymentDto): Payment
    {
        $paymentService = app(AsaasPaymentService::class);
        $payment = $paymentService->pay($paymentDto);
        return $payment;
    }
}
