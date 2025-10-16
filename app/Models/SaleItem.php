<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quantity',
        'sale_id',
        'product_id',
        'price_modification_id',
    ];

    public function sale(): BelongsTo{
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }

    public function priceModification(): BelongsTo{
        return $this->belongsTo(PriceModification::class);
    }
}
