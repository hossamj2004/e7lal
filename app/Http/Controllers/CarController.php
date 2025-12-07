<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $filter = ['status' => 'available'];

        // Apply filters from request
        if ($request->filled('brand')) {
            $filter['brand'] = $request->brand;
        }
        if ($request->filled('year')) {
            $filter['year'] = $request->year;
        }
        if ($request->filled('fuel_type')) {
            $filter['fuel_type'] = $request->fuel_type;
        }
        if ($request->filled('price_min')) {
            $filter['price_min'] = $request->price_min;
        }
        if ($request->filled('price_max')) {
            $filter['price_max'] = $request->price_max;
        }

        $cars = Car::searchQuery([
            'filter' => $filter,
            'sort' => $request->get('sort', '-created_at')
        ])->paginate(12);

        // Get user's active car for difference calculation
        $activeCar = null;
        if (auth()->check()) {
            $activeCar = auth()->user()->getActiveCar();
        }

        // Get unique brands for filter
        $brands = Car::where('status', 'available')
            ->distinct()
            ->pluck('brand')
            ->sort();

        return view('pages.cars', compact('cars', 'activeCar', 'brands'));
    }

    public function show(Car $car)
    {
        // Get user's active car for difference calculation
        $activeCar = null;
        $difference = null;

        if (auth()->check()) {
            $activeCar = auth()->user()->getActiveCar();
            if ($activeCar && $activeCar->fair_price) {
                $difference = $activeCar->calculateDifference($car);
            }
        }

        return view('pages.car-details', compact('car', 'activeCar', 'difference'));
    }
}


