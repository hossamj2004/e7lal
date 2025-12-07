<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Saving\UserSavingService;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::searchQuery([
            'filter' => ['is_admin' => false],
            'sort' => '-created_at'
        ])->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'is_admin' => 'boolean',
        ]);

        try {
            app(UserSavingService::class)->saveAndCommit($validated);
            return redirect()->route('admin.users.index')
                ->with('success', 'تم إنشاء المستخدم بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(User $user)
    {
        $user->load('userCars', 'offers.car');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'is_admin' => 'boolean',
        ]);

        $validated['id'] = $user->id;

        try {
            app(UserSavingService::class)->saveAndCommit($validated);
            return redirect()->route('admin.users.index')
                ->with('success', 'تم تحديث المستخدم بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'لا يمكن حذف مدير النظام');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'لا يمكن حذف هذا المستخدم');
        }
    }
}


