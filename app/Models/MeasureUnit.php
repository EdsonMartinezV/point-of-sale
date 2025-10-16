<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeasureUnit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'abbreviation'
    ];

    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }

    public function retailProducts(): HasMany{
        return $this->hasMany(Product::class, 'retail_measure_unit_id');
    }
}
