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

    public function getBySeller(Seller $seller)
    {
        $cacheKey = "seller:{$seller->id}:sales";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($seller) {
            return $seller->load('sales');
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