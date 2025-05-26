<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_sale()
    {
        Sanctum::actingAs(User::factory()->create());

        $seller = Seller::factory()->create();

        $response = $this->postJson('/api/sales', [
            'seller_id' => $seller->id,
            'amount' => 1500,
            'sale_date' => now()->toDateString(),
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('seller_id', $seller->id)
            ->assertJsonPath('amount', '1500.00');
    }

    public function test_can_list_sales()
    {
        Sanctum::actingAs(User::factory()->create());

        Sale::factory()->count(3)->create();

        $response = $this->getJson('/api/sales');

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_can_show_a_sale_with_seller()
    {
        Sanctum::actingAs(User::factory()->create());

        $sale = Sale::factory()->create();

        $response = $this->getJson("/api/sales/{$sale->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $sale->id,
                'seller_id' => $sale->seller_id,
                'amount' => $sale->amount,
            ]);
    }

    public function test_authenticated_user_can_update_sale()
    {
        Sanctum::actingAs(User::factory()->create());

        $sale = Sale::factory()->create([
            'amount' => 100,
        ]);

        $response = $this->putJson("/api/sales/{$sale->id}", [
            'seller_id' => $sale->seller_id,
            'amount' => 200,
            'sale_date' => $sale->sale_date->toDateString(),
        ]);

        $response->assertOk()
            ->assertJsonPath('amount', '200.00');
    }

    public function test_authenticated_user_can_delete_sale()
    {
        Sanctum::actingAs(User::factory()->create());

        $sale = Sale::factory()->create();

        $response = $this->deleteJson("/api/sales/{$sale->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('sales', ['id' => $sale->id]);
    }
}
