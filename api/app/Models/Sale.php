<?php

namespace App\Models;

use App\Enums\SaleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sales';

    protected $fillable = [
        'seller_id',
        'amount',
        'commission',
        'sale_date',
        'status',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'amount' => 'decimal:2',
        'commission' => 'decimal:2',
        'status' => SaleStatus::class,
    ];

    protected static function booted()
    {
        static::saved(function (Sale $sale) {
            $sale->seller?->forgetSalesCache();
            static::forgetGeneralSalesCache();
        });

        static::deleted(function (Sale $sale) {
            $sale->seller?->forgetSalesCache();
            static::forgetGeneralSalesCache();
        });
    }

    protected static function forgetGeneralSalesCache(): void
    {
        foreach (range(1, 100) as $page) {
            Cache::forget("sales:page:$page");
        }
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class)->withTrashed();
    }
}
