<?php

namespace Database\Seeders;

use App\Models\BillingType;
use Illuminate\Database\Seeder;

class BillingTypeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        BillingType::factory()->create([
            'type' => BillingType::BOLETO,
            'description' => 'Boleto',
        ]);
        BillingType::factory()->create([
            'type' => BillingType::CARTAO_CREDITO,
            'description' => 'Credit card',
        ]);
        BillingType::factory()->create([
            'type' => BillingType::PIX,
            'description' => 'Pix',
        ]);
    }
}
