<?php

namespace App\Services;

use App\Models\Seller;

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
}