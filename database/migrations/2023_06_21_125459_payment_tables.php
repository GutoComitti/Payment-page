<?php

use App\Models\BillingType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billing_types', function(Blueprint $table)
        {
	        $table->collation = 'utf8mb4_bin';
            $table->id('id');
            $table->text('type');
            $table->text('description');
            $table->timestamps();
        });
        Schema::create('payments', function(Blueprint $table)
        {
            $table->id('id');
            $table->float('value');
            $table->foreignIdFor(BillingType::class, 'billing_type_id');
            $table->timestamps();
            $table->text('external_billing_id');
            /**
             * Considering factors like maintenance and scalability for a bigger system, it would be better
             * to have a model for each type of payment, where PaymentType BelongsTo Payment
             * Ex.: Boleto BelongsTo Payment
             * Because of the complexity and time it would take, I'm just putting all the information in one table, but
             * the ideal would be to have a general table with payment informations, and the specific information to be
             * put on the specific type of payment table (ex.: boleto.payment_id = payment.id).
             */
            // Boleto:
            $table->text('payment_link')->nullable();
            // Pix:
            $table->text('pix_encoded_image')->nullable();
            $table->text('pix_qr_code_payload')->nullable();
            $table->date('pix_expiration_date')->nullable();
            // Credit card:
            // Credit card and pix:
            $table->text('customer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
