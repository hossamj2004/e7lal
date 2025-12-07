<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserCar;
use App\Services\Saving\UserCarSavingService;
use Illuminate\Http\Request;

class AdminUserCarController extends Controller
{
    public function index(Request $request)
    {
        $filter = [];
        
        if ($request->filled('status')) {
            $filter['status'] = $request->status;
        }

        $userCars = UserCar::searchQuery([
            'filter' => $filter,
            'sort' => '-created_at',
            'with' => ['user']
        ])->paginate(15);

        return view('admin.user-cars.index', compact('userCars'));
    }

    public function pending()
    {
        $userCars = UserCar::searchQuery([
            'filter' => ['status' => 'pending'],
            'sort' => '-created_at',
            'with' => ['user']
        ])->paginate(15);

        return view('admin.user-cars.pending', compact('userCars'));
    }

    public function show(UserCar $userCar)
    {
        $userCar->load('user', 'offers');
        return view('admin.user-cars.show', compact('userCar'));
    }

    public function price(Request $request, UserCar $userCar)
    {
        $validated = $request->validate([
            'fair_price' => 'required|numeric|min:1',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $validated['id'] = $userCar->id;
        $validated['status'] = 'priced';

        try {
            app(UserCarSavingService::class)->saveAndCommit($validated);
            return redirect()->route('admin.user-cars.pending')
                ->with('success', 'تم تسعير العربية بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function reject(Request $request, UserCar $userCar)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $validated['id'] = $userCar->id;
        $validated['status'] = 'rejected';

        try {
            app(UserCarSavingService::class)->saveAndCommit($validated);
            return redirect()->route('admin.user-cars.pending')
                ->with('success', 'تم رفض العربية');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}


