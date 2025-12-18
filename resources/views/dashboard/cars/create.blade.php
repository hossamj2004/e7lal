@extends('layouts.dashboard')

@section('title', 'أضف سيارة - E7lal.com')
@section('page-title', 'أضف سيارة جديدة')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-car-front me-2"></i>بيانات السيارة</h5>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <form action="{{ route('my-cars.store') }}" method="POST">
                @csrf
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الماركة <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" required placeholder="مثال: تويوتا">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الموديل <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control" value="{{ old('model') }}" required placeholder="مثال: كورولا">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">سنة الصنع <span class="text-danger">*</span></label>
                        <select name="year" class="form-select" required>
                            <option value="">اختر السنة</option>
                            @for($y = date('Y') + 1; $y >= 1990; $y--)
                                <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">اللون</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color') }}" placeholder="مثال: أبيض">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">الكيلومترات <span class="text-danger">*</span></label>
                        <input type="number" name="mileage" class="form-control" value="{{ old('mileage') }}" required placeholder="مثال: 50000">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">نوع الوقود <span class="text-danger">*</span></label>
                        <select name="fuel_type" class="form-select" required>
                            <option value="">اختر نوع الوقود</option>
                            <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>بنزين</option>
                            <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>ديزل</option>
                            <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>هايبرد</option>
                            <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>كهرباء</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ناقل الحركة <span class="text-danger">*</span></label>
                        <select name="transmission" class="form-select" required>
                            <option value="">اختر</option>
                            <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>أوتوماتيك</option>
                            <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>مانيوال</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">السعر المتوقع (جنيه) <span class="text-danger">*</span></label>
                        <input type="number" name="user_expected_price" class="form-control form-control-lg" value="{{ old('user_expected_price') }}" required placeholder="ادخل السعر اللي تتوقعه لعربيتك">
                        <small class="text-muted">هذا السعر تقديري وسيتم تقييم سيارتك من قبل فريقنا</small>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">وصف إضافي</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="أي تفاصيل إضافية عن السيارة (اختياري)">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">صور السيارة (روابط الصور)</label>
                        <div id="images-container">
                            <div class="input-group mb-2 image-input-group">
                                <input type="url" name="images[]" class="form-control" placeholder="https://example.com/car-image.jpg" value="{{ old('images.0') }}">
                                <button type="button" class="btn btn-outline-danger remove-image" style="display: none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-image">
                            <i class="bi bi-plus-circle me-1"></i>إضافة صورة أخرى
                        </button>
                        <small class="text-muted d-block mt-1">يمكنك إضافة عدة صور للسيارة من مختلف الزوايا</small>
                    </div>
                </div>
                
                <div class="border-top mt-4 pt-4">
                    <button type="submit" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-check-circle me-2"></i>إضافة السيارة
                    </button>
                    <a href="{{ route('my-cars.index') }}" class="btn btn-outline-secondary btn-lg me-2">إلغاء</a>
                </div>
            </form>

            <script>
                document.getElementById('add-image').addEventListener('click', function() {
                    const container = document.getElementById('images-container');
                    const imageGroups = container.querySelectorAll('.image-input-group');
                    const newGroup = imageGroups[0].cloneNode(true);

                    // Clear the value of the new input
                    const newInput = newGroup.querySelector('input');
                    newInput.value = '';

                    // Show remove button for all groups
                    imageGroups.forEach(group => {
                        group.querySelector('.remove-image').style.display = 'block';
                    });
                    newGroup.querySelector('.remove-image').style.display = 'block';

                    container.appendChild(newGroup);
                });

                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-image') || e.target.closest('.remove-image')) {
                        const imageGroup = e.target.closest('.image-input-group');
                        const container = document.getElementById('images-container');
                        const imageGroups = container.querySelectorAll('.image-input-group');

                        if (imageGroups.length > 1) {
                            imageGroup.remove();

                            // Hide remove button if only one input remains
                            if (container.querySelectorAll('.image-input-group').length === 1) {
                                container.querySelector('.remove-image').style.display = 'none';
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>
@endsection
