<?php

namespace Tests\Unit;

use App\Models\Sale;
use App\Models\Seller;
use App\Services\SellerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SellerService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SellerService();
    }

    public function test_get_sales_total()
    {
        $seller = Seller::factory()->create();

        $sales = Sale::factory()->count(2)->create([
            'seller_id' => $seller->id,
            'amount' => 100,
        ]);

        $total = $this->service->getSalesTotal($sales);

        $this->assertEquals(200, $total);
    }

    public function test_get_sales_commission()
    {
        $seller = Seller::factory()->create();

        $sales = Sale::factory()->count(2)->create([
            'seller_id' => $seller->id,
            'commission' => 8.5,
        ]);

        $commission = $this->service->getSalesCommission($sales);

        $this->assertEquals(17.0, $commission);
    }
}
