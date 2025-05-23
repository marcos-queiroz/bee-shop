<?php

namespace App\Models;

use App\Enums\SaleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class)->withTrashed();
    }
}
