<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardColumn;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskBoardController extends Controller
{
    public function index()
    {
        $board = Board::where('is_default', true)
            ->with(['columns.tasks' => function($query) {
                $query->withCount('comments');
            }])
            ->firstOrFail();
        $canManage = true; // Main page allows everyone to manage
        $isShareLink = false;

        return view('tasks.index', compact('board', 'canManage', 'isShareLink'));
    }

    public function show($token)
    {
        $board = Board::where('share_token', $token)
            ->with(['columns.tasks' => function($query) {
                $query->withCount('comments');
            }])
            ->firstOrFail();
        $canManage = false; // Share links are move-only
        $isShareLink = true;

        return view('tasks.index', compact('board', 'canManage', 'isShareLink'));
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
        // CSRF protection handles security - no admin check needed
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
        // CSRF protection handles security - no admin check needed
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
        // CSRF protection handles security - no admin check needed
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
        // CSRF protection handles security - no admin check needed
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

    public function deleteTask(Task $task)
    {
        // CSRF protection handles security - no admin check needed
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المهمة بنجاح',
        ]);
    }

    public function getComments(Task $task)
    {
        $comments = $task->comments()->get()->map(function ($comment) {
            return [
                'id' => $comment->id,
                'body' => $comment->body,
                'author_name' => $comment->author_name,
                'created_at' => $comment->created_at->toISOString(),
                'relative_time' => $comment->relative_time,
            ];
        });

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'is_blocked' => $task->is_blocked,
                'blocked_reason' => $task->blocked_reason,
                'comments_count' => $comments->count(),
            ],
        ]);
    }

    public function addComment(Request $request, Task $task)
    {
        // Available on both /tasks and share links
        $request->validate([
            'body' => 'required|string|max:2000',
            'author_name' => 'nullable|string|max:100',
        ]);

        $comment = $task->comments()->create([
            'body' => $request->body,
            'author_name' => $request->author_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة التعليق بنجاح',
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'author_name' => $comment->author_name,
                'created_at' => $comment->created_at->toISOString(),
                'relative_time' => $comment->relative_time,
            ],
        ]);
    }

    public function deleteComment(TaskComment $comment)
    {
        // Only available on /tasks (canManage), enforced by UI
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التعليق بنجاح',
        ]);
    }

    public function updateBlocked(Request $request, Task $task)
    {
        // Available on both /tasks and share links
        $request->validate([
            'is_blocked' => 'required|boolean',
            'blocked_reason' => 'nullable|string|max:500',
        ]);

        // If blocking, reason is required
        if ($request->is_blocked && empty($request->blocked_reason)) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إدخال سبب التوقف',
            ], 422);
        }

        $task->update([
            'is_blocked' => $request->is_blocked,
            'blocked_reason' => $request->is_blocked ? $request->blocked_reason : null,
        ]);

        // Auto-add comment recording the block/unblock
        if ($request->is_blocked) {
            $task->comments()->create([
                'body' => "🔴 التاسك واقفة: {$request->blocked_reason}",
                'author_name' => $request->author_name ?? null,
            ]);
        } else {
            $task->comments()->create([
                'body' => "✅ التاسك رجعت تشتغل تاني",
                'author_name' => $request->author_name ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $request->is_blocked ? 'تم تسجيل التوقف' : 'تم إلغاء التوقف',
            'task' => [
                'id' => $task->id,
                'is_blocked' => $task->is_blocked,
                'blocked_reason' => $task->blocked_reason,
            ],
        ]);
    }
}
