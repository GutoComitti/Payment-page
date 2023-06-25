<?php

namespace App\DataTransferObjects;

use App\Http\Requests\PaymentRequest;

readonly class BoletoPaymentDto extends PaymentDto
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

    protected static function getDto(PaymentRequest $request): BoletoPaymentDto{
        return new self(
            name: $request->post('name'),
            remoteIp: $request->ip(),
            billingType: $request->post('billingType'),
            amount: $request->post('amount'),
            cpf: $request->post('cpf'),
        );
    }
}
