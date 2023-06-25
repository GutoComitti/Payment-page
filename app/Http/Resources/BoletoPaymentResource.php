<?php

namespace App\Http\Resources;

class BoletoPaymentResource extends PaymentResource
{
    public function toArray($request)
    {
        $requestArray = parent::toArray($request);
        return $requestArray;
    }
}
