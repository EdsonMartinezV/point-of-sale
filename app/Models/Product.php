<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'stock',
        'cost_price',
        'first_sale_percentage',
        'second_sale_percentage',
        'third_sale_percentage',
    ];

    public function category(): BelongsTo{
        return $this->belongsTo(Category::class);
    }

    public function purchaseItems(): HasMany{
        return $this->hasMany(PurchaseItem::class);
    }

    public function priceModifications(): HasMany{
        return $this->hasMany(PriceModification::class);
    }

    public function saleItems(): HasMany{
        return $this->hasMany(SaleItem::class);
    }
}
