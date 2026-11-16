<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'title',
        'description',
        'status',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status)->orderBy('position');
    }
}
