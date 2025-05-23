<?php

namespace App\Services;

use App\Models\Sale;

class SaleService
{
  public function all()
  {
    return Sale::with('seller')->get();
  }

  public function create(array $data): Sale
  {
    return Sale::create($data);
  }

  public function update(Sale $sale, array $data): Sale
  {
    $sale->update($data);
    return $sale;
  }

  public function delete(Sale $sale): void
  {
    $sale->delete();
  }

  public function updateCommision(Sale $sale, array $data): Sale
  {
    $data['commission'] = $this->calculateCommission($data['amount']);
    $data['status'] = 'calculated';

    $sale->update($data);
    return $sale;
  }

  protected function calculateCommission(float $amount): float
  {
    return round($amount * 0.085, 2); // 8.5% de comissão
  }
}
