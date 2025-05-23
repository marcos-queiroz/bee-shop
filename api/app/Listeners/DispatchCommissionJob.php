<?php

namespace App\Listeners;

use App\Events\SaleUpdated;
use App\Jobs\CalculateCommissionJob;

class DispatchCommissionJob
{
    /**
     * Handle the event.
     */
    public function handle(SaleUpdated $event): void
    {
        CalculateCommissionJob::dispatch($event->sale);
    }
}
