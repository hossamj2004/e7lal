@extends('layouts.dashboard')

@section('title', 'العروض الجديدة - Swape It')
@section('page-title', 'العروض الجديدة')

@section('content')
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المستخدم</th>
                    <th>سيارته</th>
                    <th>السيارة المطلوبة</th>
                    <th>الفرق المعروض</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($offers as $offer)
                    <tr>
                        <td>{{ $offer->id }}</td>
                        <td>
                            <strong>{{ $offer->user->name }}</strong>
                            <br><small class="text-muted">{{ $offer->user->phone ?? $offer->user->email }}</small>
                        </td>
                        <td>
                            {{ $offer->userCar->full_name }}
                            <br><small class="text-success">{{ $offer->userCar->formatted_fair_price }}</small>
                        </td>
                        <td>
                            {{ $offer->car->full_name }}
                            <br><small class="text-primary">{{ $offer->car->formatted_price }}</small>
                        </td>
                        <td class="fw-bold {{ $offer->offered_difference > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $offer->formatted_difference }}
                        </td>
                        <td>{{ $offer->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.offers.show', $offer) }}" class="btn btn-sm btn-primary-custom">
                                <i class="bi bi-eye me-1"></i>عرض
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-check-circle text-success mb-3" style="font-size: 4rem;"></i>
                            <p class="text-muted">لا توجد عروض جديدة</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $offers->links() }}
</div>
@endsection


