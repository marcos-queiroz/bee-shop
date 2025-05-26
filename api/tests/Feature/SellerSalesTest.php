<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SellerSalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_sales_by_seller_with_totals()
    {
        Sanctum::actingAs(User::factory()->create());

        $seller = Seller::factory()->create();

        Sale::factory()->count(2)->create([
            'seller_id' => $seller->id,
            'amount' => 100,
            'commission' => 8.5,
        ]);

        $response = $this->getJson("/api/sellers/{$seller->id}/sales");

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'total',
                'per_page',
                'total_amount',
                'total_commission',
            ])
            ->assertJsonPath('total_amount', '200.00')
            ->assertJsonPath('total_commission', '17.00');
    }
}
