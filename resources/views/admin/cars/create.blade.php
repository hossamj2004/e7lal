@extends('layouts.dashboard')

@section('title', 'إضافة سيارة - E7lal.com')
@section('page-title', 'إضافة سيارة جديدة')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الماركة <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الموديل <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control" value="{{ old('model') }}" required>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">سنة الصنع <span class="text-danger">*</span></label>
                        <select name="year" class="form-select" required>
                            @for($y = date('Y') + 1; $y >= 1990; $y--)
                                <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">اللون</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color') }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">الكيلومترات <span class="text-danger">*</span></label>
                        <input type="number" name="mileage" class="form-control" value="{{ old('mileage', 0) }}" required>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">نوع الوقود <span class="text-danger">*</span></label>
                        <select name="fuel_type" class="form-select" required>
                            <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>بنزين</option>
                            <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>ديزل</option>
                            <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>هايبرد</option>
                            <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>كهرباء</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ناقل الحركة <span class="text-danger">*</span></label>
                        <select name="transmission" class="form-select" required>
                            <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>أوتوماتيك</option>
                            <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>مانيوال</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">الحالة <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>متاحة</option>
                            <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>محجوزة</option>
                            <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>مباعة</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">السعر (جنيه) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control form-control-lg" value="{{ old('price') }}" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">الوصف</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">صورة السيارة</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                    </div>
                </div>
                
                <div class="border-top mt-4 pt-4">
                    <button type="submit" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-check-circle me-2"></i>حفظ
                    </button>
                    <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary btn-lg me-2">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
