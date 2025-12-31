@extends('layouts.dashboard')

@section('title', 'إضافة طلب تبديل جديد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إضافة طلب تبديل جديد</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.exchange-requests.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> العودة للقائمة
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.exchange-requests.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="car_model" class="form-label">نوع السيارة <span class="text-danger">*</span></label>
                                    <input type="text" name="car_model" id="car_model" class="form-control" placeholder="مثال: هيونداي فيرنا 2010" value="{{ old('car_model') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="car_price" class="form-label">سعر السيارة</label>
                                    <input type="number" name="car_price" id="car_price" class="form-control" placeholder="مثال: 150000" value="{{ old('car_price') }}" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="01xxxxxxxxx" value="{{ old('phone') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location" class="form-label">الموقع <span class="text-danger">*</span></label>
                                    <input type="text" name="location" id="location" class="form-control" placeholder="مثال: مدينة نصر" value="{{ old('location') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="desired_price_range" class="form-label">الرينج السعري المطلوب</label>
                                    <select name="desired_price_range" id="desired_price_range" class="form-select">
                                        <option value="">اختار الرينج المطلوب</option>
                                        <option value="50-200" {{ old('desired_price_range') == '50-200' ? 'selected' : '' }}>50–200 ألف</option>
                                        <option value="200-300" {{ old('desired_price_range') == '200-300' ? 'selected' : '' }}>200–300 ألف</option>
                                        <option value="300-400" {{ old('desired_price_range') == '300-400' ? 'selected' : '' }}>300–400 ألف</option>
                                        <option value="400-500" {{ old('desired_price_range') == '400-500' ? 'selected' : '' }}>400–500 ألف</option>
                                        <option value="500+" {{ old('desired_price_range') == '500+' ? 'selected' : '' }}>أكثر من 500 ألف</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ad_link" class="form-label">رابط الإعلان</label>
                                    <input type="url" name="ad_link" id="ad_link" class="form-control" placeholder="https://olx.com.eg/ad/..." value="{{ old('ad_link') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="admin_notes" class="form-label">ملاحظات المدير</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="أضف أي ملاحظات خاصة بالطلب...">{{ old('admin_notes') }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ الطلب
                        </button>
                        <a href="{{ route('admin.exchange-requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    }

    .btn-secondary {
        border-radius: 25px;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }
</style>
@endpush
@endsection