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
        'is_favorite',
        'admin_notes',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'car_price' => 'decimal:2',
        'is_favorite' => 'boolean',
        'responded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isFavorite(): bool
    {
        return $this->is_favorite;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">في الانتظار</span>',
            'in_progress' => '<span class="badge bg-info">قيد المعالجة</span>',
            'completed' => '<span class="badge bg-success">مكتملة</span>',
            'cancelled' => '<span class="badge bg-secondary">ملغية</span>',
            default => '<span class="badge bg-light">غير معروف</span>'
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->car_price ? number_format($this->car_price, 0) . ' جنيه' : 'غير محدد';
    }

    public function toggleFavorite(): void
    {
        $this->update(['is_favorite' => !$this->is_favorite]);
    }

    public function markAsResponded(): void
    {
        $this->update([
            'status' => 'in_progress',
            'responded_at' => now()
        ]);
    }
}
