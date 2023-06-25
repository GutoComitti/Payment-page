<?php

namespace App\Http\Resources;

class PixPaymentResource extends PaymentResource
{
    public function toArray($request)
    {
        $requestArray = parent::toArray($request);
        return $requestArray;
    }
}
