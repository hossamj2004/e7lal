<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardColumn;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskBoardController extends Controller
{
    public function index()
    {
        $board = Board::where('is_default', true)->with('columns.tasks')->firstOrFail();
        $isAdmin = auth()->check() && auth()->user()->isAdmin();

        return view('tasks.index', compact('board', 'isAdmin'));
    }

    public function show($token)
    {
        $board = Board::where('share_token', $token)->with('columns.tasks')->firstOrFail();
        $isAdmin = auth()->check() && auth()->user()->isAdmin();

        return view('tasks.index', compact('board', 'isAdmin'));
    }

    public function updateTask(Request $request, Task $task)
    {
        $request->validate([
            'column_id' => 'required|exists:board_columns,id',
            'position' => 'required|integer|min:0',
        ]);

        // Verify the column belongs to the same board as the task
        $column = BoardColumn::findOrFail($request->column_id);
        if ($column->board_id !== $task->board_id) {
            return response()->json([
                'success' => false,
                'message' => 'العمود غير صالح',
            ], 422);
        }

        DB::transaction(function () use ($request, $task) {
            $oldColumnId = $task->column_id;
            $newColumnId = $request->column_id;
            $newPosition = $request->position;

            if ($oldColumnId !== $newColumnId) {
                // Moving to a different column
                Task::where('column_id', $oldColumnId)
                    ->where('position', '>', $task->position)
                    ->decrement('position');

                Task::where('column_id', $newColumnId)
                    ->where('position', '>=', $newPosition)
                    ->increment('position');
            } else {
                // Reordering within the same column
                if ($newPosition < $task->position) {
                    Task::where('column_id', $newColumnId)
                        ->whereBetween('position', [$newPosition, $task->position - 1])
                        ->increment('position');
                } elseif ($newPosition > $task->position) {
                    Task::where('column_id', $newColumnId)
                        ->whereBetween('position', [$task->position + 1, $newPosition])
                        ->decrement('position');
                }
            }

            $task->update([
                'column_id' => $newColumnId,
                'position' => $newPosition,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المهمة بنجاح',
        ]);
    }

    public function addColumn(Request $request, Board $board)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك',
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $maxPosition = $board->columns()->max('position') ?? -1;

        $column = BoardColumn::create([
            'board_id' => $board->id,
            'name' => $request->name,
            'position' => $maxPosition + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة العمود بنجاح',
            'column' => $column,
        ]);
    }

    public function updateColumn(Request $request, BoardColumn $column)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك',
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $column->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث العمود بنجاح',
            'column' => $column,
        ]);
    }

    public function deleteColumn(BoardColumn $column)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك',
            ], 403);
        }

        if ($column->tasks()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف عمود يحتوي على مهام',
            ], 422);
        }

        $column->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف العمود بنجاح',
        ]);
    }

    public function addTask(Request $request, BoardColumn $column)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك',
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $maxPosition = $column->tasks()->max('position') ?? -1;

        $task = Task::create([
            'board_id' => $column->board_id,
            'column_id' => $column->id,
            'title' => $request->title,
            'description' => $request->description,
            'position' => $maxPosition + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المهمة بنجاح',
            'task' => $task,
        ]);
    }
}
