<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\Models\UserCar;
use App\Models\Offer;
use App\Models\ExchangeRequest;

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
            'exchange_requests' => [
                'total' => ExchangeRequest::count(),
                'pending' => ExchangeRequest::where('status', 'pending')->count(),
                'in_progress' => ExchangeRequest::where('status', 'in_progress')->count(),
                'favorites' => ExchangeRequest::where('is_favorite', true)->count(),
            ],
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

        // Recent pending exchange requests
        $pendingExchangeRequests = ExchangeRequest::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingUserCars', 'pendingOffers', 'pendingExchangeRequests'));
    }
}
