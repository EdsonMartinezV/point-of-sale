<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = [
        'total',
        'paid_amount',
        'change_amount',
        'user_id',
        'provider_id',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function provider(): BelongsTo{
        return $this->belongsTo(Provider::class);
    }

    public function purchaseItems(){
        return $this->hasMany(PurchaseItem::class);
    }
}
