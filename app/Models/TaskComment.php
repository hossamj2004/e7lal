<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'body',
        'author_name',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    
    public function getRelativeTimeAttribute()
    {
        $diff = $this->created_at->diffInMinutes(now());
        
        if ($diff < 1) {
            return 'الآن';
        } elseif ($diff < 60) {
            return "منذ {$diff} دقيقة";
        } elseif ($diff < 1440) {
            $hours = floor($diff / 60);
            return "منذ {$hours} ساعة";
        } else {
            $days = floor($diff / 1440);
            return "منذ {$days} يوم";
        }
    }
}
