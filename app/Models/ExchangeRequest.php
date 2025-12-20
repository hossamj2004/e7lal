<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRequest extends Model
{
    protected $fillable = [
        'car_model',
        'car_price',
        'desired_price_range',
        'location',
        'ad_link',
        'phone',
        'user_id',
    ];

    protected $casts = [
        'car_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
