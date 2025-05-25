<?php

namespace App\Services;

use App\Models\Seller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SellerService
{
    public function all()
    {
        return Seller::all();
    }

    public function create(array $data): Seller
    {
        return Seller::create($data);
    }

    public function update(Seller $seller, array $data): Seller
    {
        $seller->update($data);
        return $seller;
    }

    public function delete(Seller $seller): void
    {
        $seller->delete();
    }

    public function getBySellerPaginated(Seller $seller)
    {
        $page = request('page', 1);
        $cacheKey = "seller:{$seller->id}:sales:page:$page";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($seller) {
            $salesQuery = $seller->sales();

            $totalAmount = (clone $salesQuery)->sum('amount');
            $totalCommission = (clone $salesQuery)->sum('commission');

            $sales = $salesQuery->paginate(10);

            return [
                ...$sales->toArray(),
                'total_amount' => $totalAmount,
                'total_commission' => $totalCommission,
            ];
        });
    }

    public function getSellersWithSalesByDate(\DateTimeInterface $date): Collection
    {
        return Seller::with(['sales' => fn($q) => $q->whereDate('sale_date', $date)])
            ->get();
    }

    public function getSalesTotal(Collection $sales): float
    {
        return $sales->sum('amount');
    }

    public function getSalesCommission(Collection $sales): float
    {
        return $sales->sum('commission');
    }
}
