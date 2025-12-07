<?php

namespace App\Http\Controllers;

use App\Models\UserCar;
use App\Services\Saving\UserCarSavingService;
use Illuminate\Http\Request;

class UserCarController extends Controller
{
    public function index()
    {
        $userCars = UserCar::search([
            'filter' => ['user_id' => auth()->id()],
            'sort' => '-created_at'
        ]);
        
        return view('dashboard.cars.index', compact('userCars'));
    }

    public function create()
    {
        return view('dashboard.cars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'mileage' => 'required|integer|min:0',
            'fuel_type' => 'required|in:petrol,diesel,hybrid,electric',
            'transmission' => 'required|in:automatic,manual',
            'user_expected_price' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['user_id'] = auth()->id();

        try {
            $userCar = app(UserCarSavingService::class)->saveAndCommit($validated);
            return redirect()->route('my-cars.index')
                ->with('success', 'تم إضافة عربيتك بنجاح وهي الآن قيد المراجعة');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(UserCar $userCar)
    {
        // Ensure user owns this car
        if ($userCar->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('dashboard.cars.show', compact('userCar'));
    }

    public function setActive(UserCar $userCar)
    {
        // Ensure user owns this car
        if ($userCar->user_id !== auth()->id()) {
            abort(403);
        }

        // Only priced cars can be set as active
        if ($userCar->status !== 'priced') {
            return back()->with('error', 'لا يمكن تفعيل عربية لم يتم تقييمها بعد');
        }

        try {
            app(UserCarSavingService::class)->saveAndCommit([
                'id' => $userCar->id,
                'is_active' => true
            ]);
            return back()->with('success', 'تم تفعيل العربية بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}


