<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        // Parent class to unset the fields all types of payment should unset.
        $requestArray = parent::toArray($request);
        unset($requestArray["created_at"]);
        unset($requestArray["updated_at"]);
        unset($requestArray["billing_type_id"]);
        unset($requestArray["billing_type"]);
        unset($requestArray["external_billing_id"]);
        return $requestArray;
    }
}
