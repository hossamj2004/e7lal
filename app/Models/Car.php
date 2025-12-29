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
        'images',
        'youtube_video',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'year' => 'integer',
        'mileage' => 'integer',
        'images' => 'array',
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

    // Image helper methods
    public function hasImages(): bool
    {
        return !empty($this->images) && is_array($this->images);
    }

    public function getFirstImage(): ?string
    {
        return $this->hasImages() ? $this->images[0] : null;
    }

    public function getImageCount(): int
    {
        return $this->hasImages() ? count($this->images) : 0;
    }

    // YouTube video helper methods
    public function hasYouTubeVideo(): bool
    {
        return !empty($this->youtube_video);
    }

    public function getYouTubeVideoId(): ?string
    {
        if (!$this->hasYouTubeVideo()) {
            return null;
        }

        $url = $this->youtube_video;

        // Extract video ID from various YouTube URL formats
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        // If it's already just the video ID
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        return null;
    }

    public function getYouTubeEmbedUrl(): ?string
    {
        $videoId = $this->getYouTubeVideoId();
        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }
}
