<?php

namespace App\Events;

use App\Models\Sale;
use Illuminate\Foundation\Events\Dispatchable;

class SaleUpdated
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public Sale $sale) {}
}
