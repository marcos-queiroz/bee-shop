<?php

namespace App\Services;

use App\Enums\SaleStatus;
use App\Events\SaleUpdated;
use App\Models\Sale;

class SaleService
{
  public function all()
  {
    return Sale::with('seller')->get();
  }

  public function create(array $data): Sale
  {
    $sale = Sale::create([
      ...$data,
      'commission' => 0,
      'status' => SaleStatus::Received,
    ]);

    SaleUpdated::dispatch($sale);
    return $sale;
  }

  public function update(Sale $sale, array $data): Sale
  {
    $sale->update([
      ...$data,
      'commission' => 0,
      'status' => SaleStatus::Received,
    ]);

    SaleUpdated::dispatch($sale);
    return $sale;
  }

  public function delete(Sale $sale): void
  {
    $sale->delete();
  }

  public function updateCommision(Sale $sale): Sale
  {
    $data['commission'] = $this->calculateCommission($sale->amount);
    $data['status'] = 'calculated';

    $sale->update($data);
    return $sale;
  }

  protected function calculateCommission(float $amount): float
  {
    return round($amount * 0.085, 2); // 8.5% de comissão
  }
}
