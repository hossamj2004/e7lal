<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskBoardController extends Controller
{
    public function index()
    {
        $board = Board::where('is_default', true)->firstOrFail();
        
        $tasks = [
            'todo' => $board->tasks()->byStatus('todo')->get(),
            'in_progress' => $board->tasks()->byStatus('in_progress')->get(),
            'done' => $board->tasks()->byStatus('done')->get(),
        ];

        return view('tasks.index', compact('board', 'tasks'));
    }

    public function show($token)
    {
        $board = Board::where('share_token', $token)->firstOrFail();
        
        $tasks = [
            'todo' => $board->tasks()->byStatus('todo')->get(),
            'in_progress' => $board->tasks()->byStatus('in_progress')->get(),
            'done' => $board->tasks()->byStatus('done')->get(),
        ];

        return view('tasks.index', compact('board', 'tasks'));
    }

    public function updateTask(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,done',
            'position' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $task) {
            $oldStatus = $task->status;
            $newStatus = $request->status;
            $newPosition = $request->position;

            if ($oldStatus !== $newStatus) {
                Task::where('board_id', $task->board_id)
                    ->where('status', $oldStatus)
                    ->where('position', '>', $task->position)
                    ->decrement('position');

                Task::where('board_id', $task->board_id)
                    ->where('status', $newStatus)
                    ->where('position', '>=', $newPosition)
                    ->increment('position');
            } else {
                if ($newPosition < $task->position) {
                    Task::where('board_id', $task->board_id)
                        ->where('status', $newStatus)
                        ->whereBetween('position', [$newPosition, $task->position - 1])
                        ->increment('position');
                } elseif ($newPosition > $task->position) {
                    Task::where('board_id', $task->board_id)
                        ->where('status', $newStatus)
                        ->whereBetween('position', [$task->position + 1, $newPosition])
                        ->decrement('position');
                }
            }

            $task->update([
                'status' => $newStatus,
                'position' => $newPosition,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المهمة بنجاح',
        ]);
    }
}
