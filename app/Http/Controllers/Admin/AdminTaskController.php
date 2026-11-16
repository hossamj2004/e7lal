<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Task;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $boards = Board::with('tasks')->get();
        $defaultBoard = Board::where('is_default', true)->first();
        
        return view('admin.tasks.index', compact('boards', 'defaultBoard'));
    }

    public function create()
    {
        $boards = Board::all();
        return view('admin.tasks.create', compact('boards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'board_id' => 'required|exists:boards,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $maxPosition = Task::where('board_id', $request->board_id)
            ->where('status', $request->status)
            ->max('position') ?? -1;

        Task::create([
            'board_id' => $request->board_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'position' => $maxPosition + 1,
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'تم إضافة المهمة بنجاح');
    }

    public function edit(Task $task)
    {
        $boards = Board::all();
        return view('admin.tasks.edit', compact('task', 'boards'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'board_id' => 'required|exists:boards,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update([
            'board_id' => $request->board_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'تم تحديث المهمة بنجاح');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', 'تم حذف المهمة بنجاح');
    }

    public function boards()
    {
        $boards = Board::withCount('tasks')->get();
        return view('admin.tasks.boards', compact('boards'));
    }

    public function createBoard()
    {
        return view('admin.tasks.create-board');
    }

    public function storeBoard(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Board::create([
            'name' => $request->name,
            'is_default' => false,
        ]);

        return redirect()->route('admin.tasks.boards')
            ->with('success', 'تم إضافة اللوحة بنجاح');
    }

    public function destroyBoard(Board $board)
    {
        if ($board->is_default) {
            return redirect()->route('admin.tasks.boards')
                ->with('error', 'لا يمكن حذف اللوحة الافتراضية');
        }

        $board->delete();

        return redirect()->route('admin.tasks.boards')
            ->with('success', 'تم حذف اللوحة بنجاح');
    }
}
