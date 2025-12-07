<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class UserCar extends Model
{
    use Searchable;

    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'year',
        'color',
        'mileage',
        'fuel_type',
        'transmission',
        'user_expected_price',
        'fair_price',
        'description',
        'image',
        'status',
        'is_active',
        'admin_notes',
    ];

    protected $casts = [
        'user_expected_price' => 'decimal:2',
        'fair_price' => 'decimal:2',
        'year' => 'integer',
        'mileage' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePriced($query)
    {
        return $query->where('status', 'priced');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPriced(): bool
    {
        return $this->status === 'priced';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} {$this->year}";
    }

    public function getFormattedExpectedPriceAttribute(): string
    {
        return number_format($this->user_expected_price, 0) . ' جنيه';
    }

    public function getFormattedFairPriceAttribute(): string
    {
        return $this->fair_price ? number_format($this->fair_price, 0) . ' جنيه' : 'قيد التقييم';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">قيد المراجعة</span>',
            'priced' => '<span class="badge bg-success">تم التقييم</span>',
            'rejected' => '<span class="badge bg-danger">مرفوضة</span>',
            default => '<span class="badge bg-light">غير معروف</span>'
        };
    }

    public function getFuelTypeArabicAttribute(): string
    {
        return match($this->fuel_type) {
            'petrol' => 'بنزين',
            'diesel' => 'ديزل',
            'hybrid' => 'هايبرد',
            'electric' => 'كهرباء',
            default => $this->fuel_type
        };
    }

    public function getTransmissionArabicAttribute(): string
    {
        return match($this->transmission) {
            'automatic' => 'أوتوماتيك',
            'manual' => 'مانيوال',
            default => $this->transmission
        };
    }

    /**
     * Calculate the price difference with a target car
     */
    public function calculateDifference(Car $car): ?float
    {
        if (!$this->fair_price) {
            return null;
        }
        return $car->price - $this->fair_price;
    }
}


