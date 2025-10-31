<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'total',
        'user_id',
        'provider_id',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function provider(): BelongsTo{
        return $this->belongsTo(Provider::class);
    }

    public function purchaseItems(): HasMany{
        return $this->hasMany(PurchaseItem::class);
    }
}
