<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Seller extends Model
{
    /** @use HasFactory<\Database\Factories\SellerFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sellers';

    protected $fillable = [
        'name',
        'email',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function forgetSalesCache()
    {
        Cache::forget("seller:{$this->id}:sales");
    }
}
