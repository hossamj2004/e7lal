<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'share_token',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($board) {
            if (empty($board->share_token)) {
                $board->share_token = Str::random(32);
            }
        });
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getShareUrlAttribute()
    {
        return route('tasks.show', $this->share_token);
    }
}
