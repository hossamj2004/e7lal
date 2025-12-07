<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Offer;
use App\Services\Saving\OfferSavingService;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::search([
            'filter' => ['user_id' => auth()->id()],
            'sort' => '-created_at',
            'with' => ['car', 'userCar']
        ]);

        return view('dashboard.offers.index', compact('offers'));
    }

    public function create(Car $car)
    {
        // Check if user has an active car
        $activeCar = auth()->user()->getActiveCar();
        
        if (!$activeCar) {
            return redirect()->route('my-cars.create')
                ->with('error', 'يجب إضافة عربية وتفعيلها أولاً قبل تقديم عرض');
        }

        if ($activeCar->status !== 'priced') {
            return redirect()->route('dashboard')
                ->with('error', 'عربيتك لم يتم تقييمها بعد');
        }

        // Check for existing pending offer
        $existingOffer = Offer::where('user_id', auth()->id())
            ->where('car_id', $car->id)
            ->where('status', 'pending')
            ->first();

        if ($existingOffer) {
            return redirect()->route('offers.index')
                ->with('error', 'لديك عرض قائم على هذه العربية بالفعل');
        }

        $suggestedDifference = $activeCar->calculateDifference($car);

        return view('dashboard.offers.create', compact('car', 'activeCar', 'suggestedDifference'));
    }

    public function store(Request $request, Car $car)
    {
        $validated = $request->validate([
            'offered_difference' => 'required|numeric',
            'message' => 'nullable|string|max:500',
        ]);

        $activeCar = auth()->user()->getActiveCar();

        $validated['user_id'] = auth()->id();
        $validated['user_car_id'] = $activeCar->id;
        $validated['car_id'] = $car->id;

        try {
            app(OfferSavingService::class)->saveAndCommit($validated);
            return redirect()->route('offers.index')
                ->with('success', 'تم إرسال عرضك بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function cancel(Offer $offer)
    {
        if ($offer->user_id !== auth()->id()) {
            abort(403);
        }

        if ($offer->status !== 'pending') {
            return back()->with('error', 'لا يمكن إلغاء هذا العرض');
        }

        $offer->update(['status' => 'cancelled']);
        return back()->with('success', 'تم إلغاء العرض بنجاح');
    }
}


