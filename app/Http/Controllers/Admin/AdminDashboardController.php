<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\Models\UserCar;
use App\Models\Offer;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_cars' => Car::count(),
            'available_cars' => Car::where('status', 'available')->count(),
            'total_users' => User::where('is_admin', false)->count(),
            'pending_user_cars' => UserCar::where('status', 'pending')->count(),
            'pending_offers' => Offer::where('status', 'pending')->count(),
        ];

        // Recent pending user cars
        $pendingUserCars = UserCar::search([
            'filter' => ['status' => 'pending'],
            'sort' => '-created_at',
            'with' => ['user'],
            'limit' => 5
        ]);

        // Recent pending offers
        $pendingOffers = Offer::search([
            'filter' => ['status' => 'pending'],
            'sort' => '-created_at',
            'with' => ['user', 'car', 'userCar'],
            'limit' => 5
        ]);

        return view('admin.dashboard', compact('stats', 'pendingUserCars', 'pendingOffers'));
    }
}


