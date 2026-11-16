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
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function column()
    {
        return $this->belongsTo(BoardColumn::class, 'column_id');
    }

    public function scopeByColumn($query, $columnId)
    {
        return $query->where('column_id', $columnId)->orderBy('position');
    }
}
