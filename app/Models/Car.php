<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use Searchable;

    protected $fillable = [
        'brand',
        'model',
        'year',
        'color',
        'mileage',
        'fuel_type',
        'transmission',
        'price',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'year' => 'integer',
        'mileage' => 'integer',
    ];

    // Relationships
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Helper methods
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} {$this->year}";
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0) . ' جنيه';
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

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'available' => '<span class="badge bg-success">متاحة</span>',
            'reserved' => '<span class="badge bg-warning">محجوزة</span>',
            'sold' => '<span class="badge bg-secondary">مباعة</span>',
            default => '<span class="badge bg-light">غير معروف</span>'
        };
    }
}


