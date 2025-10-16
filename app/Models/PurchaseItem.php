<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quantity',
        'purchase_id',
        'product_id',
    ];

    public function purchase(): BelongsTo{
        return $this->belongsTo(Purchase::class);
    }

    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }

    public function priceModification(): HasOne{
        return $this->hasOne(PriceModification::class);
    }
}
