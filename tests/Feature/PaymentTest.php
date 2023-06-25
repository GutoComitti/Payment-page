<?php

namespace Tests\Feature;

use App\Models\BillingType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
      parent::setUp();
      $this->seed();
    }

    public function test_main_route_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     */
    public function test_creating_valid_payment_works(): void
    {
        $amount = 461;
        $response = $this->post('/checkout', [
            "billingType" => BillingType::BOLETO,
            "name" => "Fulano",
            "cpf" => "64504334003",
            "amount" => $amount
        ]);
        $response->assertStatus(201);

        $this->assertDatabaseHas('payments', [
            'value' => $amount,
            'id' => $response->decodeResponseJson()["data"]["id"]
        ]);
    }
}
