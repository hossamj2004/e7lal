<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $boardId = DB::table('boards')->insertGetId([
            'name' => 'Default Board',
            'share_token' => Str::random(32),
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tasks = [
            [
                'board_id' => $boardId,
                'title' => 'مرحباً بك في لوحة المهام',
                'description' => 'هذه مهمة تجريبية. يمكنك سحبها وإفلاتها بين الأعمدة.',
                'status' => 'todo',
                'position' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $boardId,
                'title' => 'تجربة السحب والإفلات',
                'description' => 'جرب نقل هذه المهمة إلى "شغال عليها".',
                'status' => 'todo',
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $boardId,
                'title' => 'مهمة قيد التنفيذ',
                'description' => 'هذه المهمة في منتصف العمل.',
                'status' => 'in_progress',
                'position' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'board_id' => $boardId,
                'title' => 'مهمة مكتملة',
                'description' => 'تم إنجاز هذه المهمة بنجاح!',
                'status' => 'done',
                'position' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tasks')->insert($tasks);
    }

    public function down(): void
    {
        DB::table('tasks')->where('board_id', function($query) {
            $query->select('id')->from('boards')->where('is_default', true);
        })->delete();
        
        DB::table('boards')->where('is_default', true)->delete();
    }
};
