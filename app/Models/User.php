<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Searchable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // Relationships
    public function userCars()
    {
        return $this->hasMany(UserCar::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function activeUserCar()
    {
        return $this->hasOne(UserCar::class)->where('is_active', true);
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function hasActiveCar(): bool
    {
        return $this->userCars()->where('is_active', true)->exists();
    }

    public function getActiveCar()
    {
        return $this->userCars()->where('is_active', true)->first();
    }
}
