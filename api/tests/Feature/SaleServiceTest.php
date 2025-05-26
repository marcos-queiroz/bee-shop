<?php

namespace Tests\Unit;

use App\Services\SaleService;
use Tests\TestCase;

class SaleServiceTest extends TestCase
{
    public function test_calculate_commission_returns_85_percent_of_amount()
    {
        $service = new class extends SaleService {
            public function publicCalculate(float $amount): float
            {
                return $this->calculateCommission($amount);
            }
        };

        $this->assertEquals(8.5, $service->publicCalculate(100));
        $this->assertEquals(17.0, $service->publicCalculate(200));
        $this->assertEquals(0.0, $service->publicCalculate(0));
        $this->assertEquals(42.5, $service->publicCalculate(500));
    }
}
