<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use Searchable;

    protected $fillable = [
        'user_id',
        'user_car_id',
        'car_id',
        'offered_difference',
        'message',
        'status',
        'admin_response',
    ];

    protected $casts = [
        'offered_difference' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userCar()
    {
        return $this->belongsTo(UserCar::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getFormattedDifferenceAttribute(): string
    {
        $amount = number_format(abs($this->offered_difference), 0);
        if ($this->offered_difference > 0) {
            return "+ {$amount} جنيه";
        } elseif ($this->offered_difference < 0) {
            return "- {$amount} جنيه";
        }
        return "بدون فرق";
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">قيد المراجعة</span>',
            'accepted' => '<span class="badge bg-success">مقبول</span>',
            'rejected' => '<span class="badge bg-danger">مرفوض</span>',
            'cancelled' => '<span class="badge bg-secondary">ملغي</span>',
            default => '<span class="badge bg-light">غير معروف</span>'
        };
    }
}


