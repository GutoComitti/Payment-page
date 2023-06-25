<?php

namespace App\DataTransferObjects;

use App\Http\Requests\PaymentRequest;
use DateTime;

readonly class CreditCardPaymentDto extends PaymentDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $remoteIp,
        public readonly string $billingType,
        public readonly float $amount,
        public readonly string $cpf,
        public readonly string $ccPhone = '',
        public readonly string $ccAddressNumber = '',
        public readonly string $ccPostalCode = '',
        public readonly string $ccCpf = '',
        public readonly string $ccEmail = '',
        public readonly string $ccCcv = '',
        public readonly DateTime|null $ccExpiration = null,
        public readonly string $ccNumber = '',
        public readonly string $ccName = '',
        public readonly float $ccInstallmentValue = 0,
        public readonly int $ccInstallmentCount = 0,
    )
    {

    }

    protected static function getDto(PaymentRequest $request): CreditCardPaymentDto{
        if($ccExpiration = $request->post('ccExpiration', null)){
            $ccExpiration = DateTime::createFromFormat('m/y', $ccExpiration);
        }
        return new self(
            name: $request->post('name'),
            remoteIp: $request->ip(),
            billingType: $request->post('billingType'),
            amount: $request->post('amount'),
            cpf: $request->post('cpf'),
            ccPhone: $request->post('ccPhone', ''),
            ccAddressNumber: $request->post('ccAddressNumber', ''),
            ccPostalCode: $request->post('ccPostalCode', ''),
            ccCpf: $request->post('ccCpf', ''),
            ccEmail: $request->post('ccEmail', ''),
            ccCcv: $request->post('ccCcv', ''),
            ccExpiration: $ccExpiration,
            ccNumber: $request->post('ccNumber', ''),
            ccName: $request->post('ccName', ''),
            ccInstallmentValue: $request->post('ccInstallmentValue', 0),
            ccInstallmentCount: $request->post('ccInstallmentCount', 0),
        );
    }
}


