<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'column_id',
        'title',
        'description',
        'position',
        'is_blocked',
        'blocked_reason',
    ];

    protected $casts = [
        'position' => 'integer',
        'is_blocked' => 'boolean',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function column()
    {
        return $this->belongsTo(BoardColumn::class, 'column_id');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class)->orderBy('created_at', 'asc');
    }

    public function scopeByColumn($query, $columnId)
    {
        return $query->where('column_id', $columnId)->orderBy('position');
    }
}
