@extends('layouts.dashboard')

@section('title', 'إدارة السيارات - E7lal.com')
@section('page-title', 'السيارات المتاحة للتبديل')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">إدارة السيارات المعروضة للتبديل</p>
    <a href="{{ route('admin.cars.create') }}" class="btn btn-primary-custom">
        <i class="bi bi-plus-circle me-2"></i>أضف سيارة جديدة
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>السيارة</th>
                    <th>المواصفات</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cars as $car)
                    <tr>
                        <td>{{ $car->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($car->hasImages())
                                    <img src="{{ $car->getFirstImage() }}" alt="" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                                    @if($car->getImageCount() > 1)
                                        <small class="text-muted d-block">+{{ $car->getImageCount() - 1 }} أخرى</small>
                                    @endif
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                        <i class="bi bi-car-front text-muted"></i>
                                    </div>
                                @endif
                                <strong>{{ $car->full_name }}</strong>
                            </div>
                        </td>
                        <td>
                            <small>
                                {{ number_format($car->mileage) }} كم •
                                {{ $car->fuel_type_arabic }} •
                                {{ $car->transmission_arabic }}
                            </small>
                        </td>
                        <td class="fw-bold">{{ $car->formatted_price }}</td>
                        <td>{!! $car->status_badge !!}</td>
                        <td>
                            <a href="{{ route('admin.cars.edit', $car) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.cars.destroy', $car) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد؟')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $cars->links() }}
</div>
@endsection
