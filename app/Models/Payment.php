<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "value",
        "billing_type_id",
        "external_billing_id",
        "customer_id",
        "payment_link",
        "pix_encoded_image",
        "pix_qr_code_payload",
        "pix_expiration_date",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];

    public function billingType(): BelongsTo
    {
        return $this->belongsTo(BillingType::class);
    }
}
