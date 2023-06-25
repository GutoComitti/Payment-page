<?php

namespace App\Actions;

use App\DataTransferObjects\PaymentDto;
use App\Models\Payment;
use App\Services\AsaasPaymentService;
use Illuminate\Support\Facades\Session;

class PaymentAction
{
    public function execute(PaymentDto $paymentDto): Payment
    {
        Session::forget('lastPaymentId');
        $paymentService = app(AsaasPaymentService::class);
        $payment = $paymentService->pay($paymentDto);
        Session::put('lastPaymentId', $payment->id);
        return $payment;
    }
}
