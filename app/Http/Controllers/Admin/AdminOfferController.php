<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class AdminOfferController extends Controller
{
    public function index(Request $request)
    {
        $filter = [];
        
        if ($request->filled('status')) {
            $filter['status'] = $request->status;
        }

        $offers = Offer::searchQuery([
            'filter' => $filter,
            'sort' => '-created_at',
            'with' => ['user', 'car', 'userCar']
        ])->paginate(15);

        return view('admin.offers.index', compact('offers'));
    }

    public function pending()
    {
        $offers = Offer::searchQuery([
            'filter' => ['status' => 'pending'],
            'sort' => '-created_at',
            'with' => ['user', 'car', 'userCar']
        ])->paginate(15);

        return view('admin.offers.pending', compact('offers'));
    }

    public function show(Offer $offer)
    {
        $offer->load('user', 'car', 'userCar');
        return view('admin.offers.show', compact('offer'));
    }

    public function accept(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'admin_response' => 'nullable|string|max:1000',
        ]);

        $offer->update([
            'status' => 'accepted',
            'admin_response' => $validated['admin_response'] ?? null
        ]);

        // Optionally mark the car as reserved
        $offer->car->update(['status' => 'reserved']);

        return redirect()->route('admin.offers.pending')
            ->with('success', 'تم قبول العرض بنجاح');
    }

    public function reject(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|max:1000',
        ]);

        $offer->update([
            'status' => 'rejected',
            'admin_response' => $validated['admin_response']
        ]);

        return redirect()->route('admin.offers.pending')
            ->with('success', 'تم رفض العرض');
    }
}


