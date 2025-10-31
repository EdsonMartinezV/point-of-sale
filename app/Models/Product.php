<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sold_by_retail',
        'retail_units_per_box',
        'stock',
        'retail_remaining_stock',
        'cost_price',
        'first_wholesale_percentage',
        'second_wholesale_percentage',
        'third_wholesale_percentage',
        'retail_percentage',
        'category_id',
        'measure_unit_id',
        'retail_measure_unit_id',
    ];

    public function category(): BelongsTo{
        return $this->belongsTo(Category::class);
    }

    public function measureUnit(): BelongsTo{
        return $this->belongsTo(MeasureUnit::class);
    }

    public function retailMeasureUnit(): BelongsTo{
        return $this->belongsTo(MeasureUnit::class, 'retail_measure_unit_id');
    }

    public function purchaseItems(): HasMany{
        return $this->hasMany(PurchaseItem::class);
    }

    public function priceModifications(): HasMany{
        return $this->hasMany(PriceModification::class);
    }

    public function latestPriceModification(): HasOne{
        return $this->hasOne(PriceModification::class)->latestOfMany();
    }

    public function saleItems(): HasMany{
        return $this->hasMany(SaleItem::class);
    }
}
