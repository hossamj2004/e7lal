<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Services\Saving\CarSavingService;
use Illuminate\Http\Request;

class AdminCarController extends Controller
{
    public function index()
    {
        $cars = Car::searchQuery([
            'sort' => '-created_at',
            'filter' => ['disable_custom_filters' => true] // Show all cars
        ])->paginate(15);

        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
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
            'price' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:2000',
            'images' => 'nullable|array',
            'images.*' => 'nullable|url',
            'youtube_video' => 'nullable|url',
            'status' => 'required|in:available,reserved,sold',
        ]);

        try {
            app(CarSavingService::class)->saveAndCommit($validated);
            return redirect()->route('admin.cars.index')
                ->with('success', 'تم إضافة العربية بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'mileage' => 'required|integer|min:0',
            'fuel_type' => 'required|in:petrol,diesel,hybrid,electric',
            'transmission' => 'required|in:automatic,manual',
            'price' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:2000',
            'images' => 'nullable|array',
            'images.*' => 'nullable|url',
            'youtube_video' => 'nullable|url',
            'status' => 'required|in:available,reserved,sold',
        ]);

        $validated['id'] = $car->id;

        try {
            app(CarSavingService::class)->saveAndCommit($validated);
            return redirect()->route('admin.cars.index')
                ->with('success', 'تم تحديث العربية بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Car $car)
    {
        try {
            $car->delete();
            return redirect()->route('admin.cars.index')
                ->with('success', 'تم حذف العربية بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'لا يمكن حذف هذه العربية');
        }
    }
}
