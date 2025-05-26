<?php

namespace App\Services;

use App\Enums\SaleStatus;
use App\Events\SaleUpdated;
use App\Models\Sale;
use Illuminate\Support\Facades\Cache;

class SaleService
{
    public function all()
    {
        $page = request('page', 1);
        $cacheKey = "sales:page:$page";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $salesQuery = Sale::with('seller');

            $totalAmount = (clone $salesQuery)->sum('amount');
            $totalCommission = (clone $salesQuery)->sum('commission');

            $sales = $salesQuery->orderBy('sale_date', 'desc')->paginate(10);

            return [
                ...$sales->toArray(),
                'total_amount' => $totalAmount,
                'total_commission' => $totalCommission,
            ];
        });
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

    public function getSalesByDate(\DateTimeInterface|string $date)
    {
        $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;

        return Sale::with('seller')
            ->whereDate('sale_date', $date->toDateString())
            ->get();
    }

    public function getTotalBySales($sales): float
    {
        return $sales->sum('amount');
    }
}
