@extends('layouts.dashboard')

@section('title', 'سيارات المستخدمين - E7lal.com')
@section('page-title', 'كل سيارات المستخدمين')

@section('content')
<div class="mb-4">
    <div class="btn-group">
        <a href="{{ route('admin.user-cars.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">الكل</a>
        <a href="{{ route('admin.user-cars.index', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">قيد المراجعة</a>
        <a href="{{ route('admin.user-cars.index', ['status' => 'priced']) }}" class="btn {{ request('status') == 'priced' ? 'btn-primary' : 'btn-outline-primary' }}">تم تقييمها</a>
        <a href="{{ route('admin.user-cars.index', ['status' => 'rejected']) }}" class="btn {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">مرفوضة</a>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المستخدم</th>
                    <th>السيارة</th>
                    <th>السعر المتوقع</th>
                    <th>السعر العادل</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userCars as $car)
                    <tr>
                        <td>{{ $car->id }}</td>
                        <td>{{ $car->user->name }}</td>
                        <td><strong>{{ $car->full_name }}</strong></td>
                        <td>{{ $car->formatted_expected_price }}</td>
                        <td>{{ $car->formatted_fair_price }}</td>
                        <td>
                            {!! $car->status_badge !!}
                            @if($car->is_active)
                                <span class="badge bg-info">نشطة</span>
                            @endif
                        </td>
                        <td>{{ $car->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.user-cars.show', $car) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">لا توجد سيارات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $userCars->links() }}
</div>
@endsection
