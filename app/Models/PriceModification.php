<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceModification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'is_current',
        'sold_by_retail',
        'retail_units_per_box',
        'remaining_stock',
        'retail_remaining_stock',
        'cost_price',
        'first_wholesale_percentage',
        'second_wholesale_percentage',
        'third_wholesale_percentage',
        'retail_percentage',
        'product_id',
        'purchase_item_id',
    ];

    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }

    public function purchaseItem(): BelongsTo{
        return $this->belongsTo(PurchaseItem::class);
    }

    public function saleItems(): HasMany{
        return $this->hasMany(SaleItem::class);
    }
}
