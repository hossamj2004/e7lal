<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get all existing boards
        $boards = DB::table('boards')->get();
        
        foreach ($boards as $board) {
            // Create three default columns for each board
            $todoColumnId = DB::table('board_columns')->insertGetId([
                'board_id' => $board->id,
                'name' => 'To do',
                'position' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $inProgressColumnId = DB::table('board_columns')->insertGetId([
                'board_id' => $board->id,
                'name' => 'In progress',
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $finishedColumnId = DB::table('board_columns')->insertGetId([
                'board_id' => $board->id,
                'name' => 'Finished',
                'position' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Map status to column_id
            $statusToColumn = [
                'todo' => $todoColumnId,
                'in_progress' => $inProgressColumnId,
                'done' => $finishedColumnId,
            ];
            
            // Update existing tasks
            foreach ($statusToColumn as $status => $columnId) {
                DB::table('tasks')
                    ->where('board_id', $board->id)
                    ->where('status', $status)
                    ->update(['column_id' => $columnId]);
            }
        }
        
        // Add column_id to tasks table and make it required
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('column_id')->after('board_id')->nullable()->constrained('board_columns')->onDelete('cascade');
        });
        
        // Make column_id not nullable now that all tasks have been updated
        DB::statement('ALTER TABLE tasks MODIFY column_id BIGINT UNSIGNED NOT NULL');
        
        // Drop the old status column
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        // Add status column back
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', ['todo', 'in_progress', 'done'])->default('todo')->after('description');
        });
        
        // Migrate data back from columns to status
        $tasks = DB::table('tasks')
            ->join('board_columns', 'tasks.column_id', '=', 'board_columns.id')
            ->select('tasks.id', 'board_columns.name', 'board_columns.position')
            ->get();
        
        foreach ($tasks as $task) {
            $status = 'todo';
            if ($task->position == 1 || stripos($task->name, 'progress') !== false) {
                $status = 'in_progress';
            } elseif ($task->position == 2 || stripos($task->name, 'finish') !== false || stripos($task->name, 'done') !== false) {
                $status = 'done';
            }
            
            DB::table('tasks')->where('id', $task->id)->update(['status' => $status]);
        }
        
        // Drop column_id
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['column_id']);
            $table->dropColumn('column_id');
        });
    }
};
