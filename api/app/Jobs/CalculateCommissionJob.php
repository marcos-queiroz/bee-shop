<?php

namespace App\Jobs;

use App\Enums\SaleStatus;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateCommissionJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Sale $sale)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(SaleService $saleService): void
    {
        $saleService->updateCommision($this->sale);
    }
}
