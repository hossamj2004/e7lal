<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\UserCar;
use App\Models\Offer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get user's cars
        $userCars = UserCar::search([
            'filter' => ['user_id' => $user->id],
            'sort' => '-created_at'
        ]);
        
        // Get active car
        $activeCar = $user->getActiveCar();
        
        // Get user's offers
        $offers = Offer::search([
            'filter' => ['user_id' => $user->id],
            'sort' => '-created_at',
            'with' => ['car', 'userCar'],
            'limit' => 5
        ]);
        
        return view('dashboard.index', compact('userCars', 'activeCar', 'offers'));
    }
}


