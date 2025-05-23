<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = Seller::all();

        foreach ($sellers as $seller) {
            Sale::factory()
                ->count(100)
                ->make()
                ->each(function ($sale) use ($sellers) {
                    $sale->seller_id = $sellers->random()->id;
                    $sale->save();
                });
        }
    }
}
