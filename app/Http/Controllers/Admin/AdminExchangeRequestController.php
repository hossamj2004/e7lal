<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminExchangeRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ExchangeRequest::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by favorites
        if ($request->filled('favorites')) {
            $query->where('is_favorite', $request->boolean('favorites'));
        }

        // Search by phone or car model
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhere('car_model', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $exchangeRequests = $query->orderBy('is_favorite', 'desc')
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(15);

        // Get statistics
        $stats = [
            'total' => ExchangeRequest::count(),
            'pending' => ExchangeRequest::where('status', 'pending')->count(),
            'in_progress' => ExchangeRequest::where('status', 'in_progress')->count(),
            'completed' => ExchangeRequest::where('status', 'completed')->count(),
            'favorites' => ExchangeRequest::where('is_favorite', true)->count(),
        ];

        return view('admin.exchange-requests.index', compact('exchangeRequests', 'stats'));
    }

    public function show(ExchangeRequest $exchangeRequest)
    {
        $exchangeRequest->load('user');
        return view('admin.exchange-requests.show', compact('exchangeRequest'));
    }

    public function update(Request $request, ExchangeRequest $exchangeRequest)
    {
        // Determine if this is a notes-only update or full update
        if ($request->has('admin_notes') && !$request->has('status')) {
            // Notes-only update - use simple validation
            $validated = $request->validate([
                'admin_notes' => 'nullable|string|max:1000',
            ]);

            // Update only notes directly
            $exchangeRequest->update(['admin_notes' => $validated['admin_notes']]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الملاحظات بنجاح',
                'status_badge' => $exchangeRequest->status_badge
            ]);
        } else {
            // Full update with status - use Hossam Standard
            $validated = $request->validate([
                'status' => 'required|in:pending,in_progress,completed,cancelled',
                'admin_notes' => 'nullable|string|max:1000',
            ]);

            // Add ID for update operation
            $validated['id'] = $exchangeRequest->id;

            try {
                $updatedRequest = app(\App\Services\Saving\ExchangeRequestSavingService::class)->saveAndCommit($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث الطلب بنجاح',
                    'status_badge' => $updatedRequest->status_badge
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
        }
    }

    public function toggleFavorite(ExchangeRequest $exchangeRequest)
    {
        $exchangeRequest->toggleFavorite();

        return response()->json([
            'success' => true,
            'is_favorite' => $exchangeRequest->is_favorite,
            'message' => $exchangeRequest->is_favorite ? 'تمت إضافة الطلب للمفضلة' : 'تم حذف الطلب من المفضلة'
        ]);
    }

    public function create()
    {
        return view('admin.exchange-requests.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_model' => 'required|string|max:255',
            'car_price' => 'nullable|numeric|min:0',
            'desired_price_range' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'ad_link' => 'nullable|url',
            'phone' => 'required|string|max:20',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        ExchangeRequest::create([
            'car_model' => $request->car_model,
            'car_price' => $request->car_price,
            'desired_price_range' => $request->desired_price_range,
            'location' => $request->location,
            'ad_link' => $request->ad_link,
            'phone' => $request->phone,
            'admin_notes' => $request->admin_notes,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.exchange-requests.index')
            ->with('success', 'تم إنشاء طلب التبديل بنجاح');
    }

    public function destroy(ExchangeRequest $exchangeRequest)
    {
        $exchangeRequest->delete();

        return redirect()->route('admin.exchange-requests.index')
            ->with('success', 'تم حذف طلب التبديل بنجاح');
    }

    public function export(Request $request)
    {
        $query = ExchangeRequest::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $exchangeRequests = $query->get();

        // Generate CSV content
        $filename = 'exchange_requests_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($exchangeRequests) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'الاسم',
                'رقم الهاتف',
                'نوع السيارة',
                'السعر',
                'الرينج المطلوب',
                'الموقع',
                'رابط الإعلان',
                'الحالة',
                'المفضلة',
                'ملاحظات المدير',
                'تاريخ الإنشاء'
            ]);

            // CSV data
            foreach ($exchangeRequests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->user ? $request->user->name : 'زائر',
                    $request->phone,
                    $request->car_model,
                    $request->car_price,
                    $request->desired_price_range,
                    $request->location,
                    $request->ad_link,
                    $request->status,
                    $request->is_favorite ? 'نعم' : 'لا',
                    $request->admin_notes,
                    $request->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}