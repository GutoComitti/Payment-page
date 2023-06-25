<?php

namespace App\DataTransferObjects;

use App\Http\Requests\PaymentRequest;
use Illuminate\Support\Str;

readonly abstract class PaymentDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $remoteIp,
        public readonly string $billingType,
        public readonly float $amount,
        public readonly string $cpf,
    )
    {

    }

    public static function fromSuccessRequest(PaymentRequest $request): PaymentDto{
        $dtoClass = self::getPaymentDtoClass($request->post('billingType'));
        return $dtoClass::getDto($request);
    }

    private static function getPaymentDtoClass(string $billingType){
        $paymentType = Str::studly(strtolower($billingType));
        $resourceClass = 'App\\DataTransferObjects\\' . $paymentType . 'PaymentDto';
        return $resourceClass;
    }

    protected static abstract function getDto(PaymentRequest $request): PaymentDto;
}
