<?php

namespace App\Http\Resources;

class CreditCardPaymentResource extends PaymentResource
{
    public function toArray($request)
    {
        $requestArray = parent::toArray($request);
        return $requestArray;
    }
}
