@extends('layouts.dashboard')

@section('title', 'كل العروض - Swape It')
@section('page-title', 'كل العروض')

@section('content')
<div class="mb-4">
    <div class="btn-group">
        <a href="{{ route('admin.offers.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">الكل</a>
        <a href="{{ route('admin.offers.index', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">قيد المراجعة</a>
        <a href="{{ route('admin.offers.index', ['status' => 'accepted']) }}" class="btn {{ request('status') == 'accepted' ? 'btn-primary' : 'btn-outline-primary' }}">مقبولة</a>
        <a href="{{ route('admin.offers.index', ['status' => 'rejected']) }}" class="btn {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">مرفوضة</a>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المستخدم</th>
                    <th>سيارته</th>
                    <th>السيارة المطلوبة</th>
                    <th>الفرق</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($offers as $offer)
                    <tr>
                        <td>{{ $offer->id }}</td>
                        <td>{{ $offer->user->name }}</td>
                        <td>{{ $offer->userCar->full_name }}</td>
                        <td>{{ $offer->car->full_name }}</td>
                        <td class="fw-bold">{{ $offer->formatted_difference }}</td>
                        <td>{!! $offer->status_badge !!}</td>
                        <td>{{ $offer->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.offers.show', $offer) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">لا توجد عروض</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $offers->links() }}
</div>
@endsection


