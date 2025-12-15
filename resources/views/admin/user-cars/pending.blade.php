@extends('layouts.dashboard')

@section('title', 'سيارات تحتاج تسعير - E7lal.com')
@section('page-title', 'سيارات تحتاج تسعير')

@section('content')
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المستخدم</th>
                    <th>السيارة</th>
                    <th>المواصفات</th>
                    <th>السعر المتوقع</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userCars as $car)
                    <tr>
                        <td>{{ $car->id }}</td>
                        <td>
                            <strong>{{ $car->user->name }}</strong>
                            <br><small class="text-muted">{{ $car->user->email }}</small>
                        </td>
                        <td><strong>{{ $car->full_name }}</strong></td>
                        <td>
                            <small>
                                {{ number_format($car->mileage) }} كم • 
                                {{ $car->fuel_type_arabic }} • 
                                {{ $car->transmission_arabic }}
                            </small>
                        </td>
                        <td class="fw-bold">{{ $car->formatted_expected_price }}</td>
                        <td>{{ $car->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.user-cars.show', $car) }}" class="btn btn-sm btn-primary-custom">
                                <i class="bi bi-currency-dollar me-1"></i>تسعير
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-check-circle text-success mb-3" style="font-size: 4rem;"></i>
                            <p class="text-muted">لا توجد سيارات تحتاج تسعير</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $userCars->links() }}
</div>
@endsection
