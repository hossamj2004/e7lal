@extends('layouts.dashboard')

@section('title', 'تفاصيل المستخدم - Swape It')
@section('page-title', 'تفاصيل المستخدم: ' . $user->name)

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="table-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-person me-2"></i>البيانات الشخصية</h5>
            
            <table class="table table-borderless">
                <tr>
                    <td class="text-muted">الاسم</td>
                    <td class="fw-bold">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">البريد</td>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="text-muted">الهاتف</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">تاريخ التسجيل</td>
                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            </table>
            
            <div class="border-top pt-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>تعديل
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="table-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-car-front me-2"></i>سيارات المستخدم</h5>
            
            @forelse($user->userCars as $car)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <strong>{{ $car->full_name }}</strong>
                        <br><small class="text-muted">{{ $car->formatted_fair_price }}</small>
                    </div>
                    {!! $car->status_badge !!}
                </div>
            @empty
                <p class="text-muted">لا توجد سيارات مسجلة</p>
            @endforelse
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="table-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-send me-2"></i>عروض المستخدم</h5>
            
            @forelse($user->offers as $offer)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <strong>{{ $offer->car->full_name }}</strong>
                        <br><small class="text-muted">{{ $offer->formatted_difference }}</small>
                    </div>
                    {!! $offer->status_badge !!}
                </div>
            @empty
                <p class="text-muted">لا توجد عروض</p>
            @endforelse
        </div>
    </div>
</div>
@endsection


