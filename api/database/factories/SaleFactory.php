<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $value = $this->faker->randomFloat(2, 100, 1000);
        $commission = round($value * 0.085, 2); // 8.5% comissão

        $startOfMonth = now()->startOfMonth();
        $today = now();

        return [
            'seller_id' => Seller::inRandomOrder()->first()?->id ?? Seller::factory(),
            'amount' => $value,
            'commission' => $commission,
            'sale_date' => $this->faker->dateTimeBetween($startOfMonth, $today),
            'status' => 'received', // status inicial
        ];
    }
}
