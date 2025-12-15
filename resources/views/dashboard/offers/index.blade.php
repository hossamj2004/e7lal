@extends('layouts.dashboard')

@section('title', 'عروضي - E7lal.com')
@section('page-title', 'عروضي')

@section('content')
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>السيارة المطلوبة</th>
                    <th>سيارتي</th>
                    <th>الفرق المعروض</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($offers as $offer)
                    <tr>
                        <td>
                            <strong>{{ $offer->car->full_name }}</strong>
                            <br><small class="text-muted">{{ $offer->car->formatted_price }}</small>
                        </td>
                        <td>
                            {{ $offer->userCar->full_name }}
                            <br><small class="text-muted">{{ $offer->userCar->formatted_fair_price }}</small>
                        </td>
                        <td>
                            <span class="fw-bold {{ $offer->offered_difference > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $offer->formatted_difference }}
                            </span>
                        </td>
                        <td>{!! $offer->status_badge !!}</td>
                        <td>{{ $offer->created_at->format('Y-m-d') }}</td>
                        <td>
                            @if($offer->isPending())
                                <form action="{{ route('offers.cancel', $offer) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من إلغاء العرض؟')">
                                        <i class="bi bi-x-circle"></i> إلغاء
                                    </button>
                                </form>
                            @endif
                            @if($offer->admin_response)
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#responseModal{{ $offer->id }}">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                
                                <!-- Response Modal -->
                                <div class="modal fade" id="responseModal{{ $offer->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">رد الإدارة</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $offer->admin_response }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-send text-muted mb-3" style="font-size: 4rem;"></i>
                            <p class="text-muted">لم تقدم أي عروض بعد</p>
                            <a href="{{ route('cars') }}" class="btn btn-primary-custom">تصفح السيارات</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
