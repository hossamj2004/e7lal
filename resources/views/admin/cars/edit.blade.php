@extends('layouts.dashboard')

@section('title', 'تعديل سيارة - E7lal.com')
@section('page-title', 'تعديل سيارة: ' . $car->full_name)

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
            
            <form action="{{ route('admin.cars.update', $car) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الماركة <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $car->brand) }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">الموديل <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control" value="{{ old('model', $car->model) }}" required>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">سنة الصنع <span class="text-danger">*</span></label>
                        <select name="year" class="form-select" required>
                            @for($y = date('Y') + 1; $y >= 1990; $y--)
                                <option value="{{ $y }}" {{ old('year', $car->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">اللون</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color', $car->color) }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">الكيلومترات <span class="text-danger">*</span></label>
                        <input type="number" name="mileage" class="form-control" value="{{ old('mileage', $car->mileage) }}" required>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">نوع الوقود <span class="text-danger">*</span></label>
                        <select name="fuel_type" class="form-select" required>
                            <option value="petrol" {{ old('fuel_type', $car->fuel_type) == 'petrol' ? 'selected' : '' }}>بنزين</option>
                            <option value="diesel" {{ old('fuel_type', $car->fuel_type) == 'diesel' ? 'selected' : '' }}>ديزل</option>
                            <option value="hybrid" {{ old('fuel_type', $car->fuel_type) == 'hybrid' ? 'selected' : '' }}>هايبرد</option>
                            <option value="electric" {{ old('fuel_type', $car->fuel_type) == 'electric' ? 'selected' : '' }}>كهرباء</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ناقل الحركة <span class="text-danger">*</span></label>
                        <select name="transmission" class="form-select" required>
                            <option value="automatic" {{ old('transmission', $car->transmission) == 'automatic' ? 'selected' : '' }}>أوتوماتيك</option>
                            <option value="manual" {{ old('transmission', $car->transmission) == 'manual' ? 'selected' : '' }}>مانيوال</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">الحالة <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>متاحة</option>
                            <option value="reserved" {{ old('status', $car->status) == 'reserved' ? 'selected' : '' }}>محجوزة</option>
                            <option value="sold" {{ old('status', $car->status) == 'sold' ? 'selected' : '' }}>مباعة</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">السعر (جنيه) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control form-control-lg" value="{{ old('price', $car->price) }}" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">الوصف</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $car->description) }}</textarea>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">صور السيارة (روابط الصور)</label>
                        <div id="images-container">
                            @if($car->images && count($car->images) > 0)
                                @foreach($car->images as $index => $image)
                                    <div class="input-group mb-2 image-input-group">
                                        <input type="url" name="images[]" class="form-control" placeholder="https://example.com/image.jpg" value="{{ old('images.' . $index, $image) }}">
                                        <button type="button" class="btn btn-outline-danger remove-image" style="{{ count($car->images) > 1 ? '' : 'display: none;' }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2 image-input-group">
                                    <input type="url" name="images[]" class="form-control" placeholder="https://example.com/image.jpg" value="{{ old('images.0') }}">
                                    <button type="button" class="btn btn-outline-danger remove-image" style="display: none;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-image">
                            <i class="bi bi-plus-circle me-1"></i>إضافة صورة أخرى
                        </button>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">فيديو يوتيوب (اختياري)</label>
                        <input type="url" name="youtube_video" class="form-control" placeholder="https://www.youtube.com/watch?v=VIDEO_ID أو https://youtu.be/VIDEO_ID" value="{{ old('youtube_video', $car->youtube_video) }}">
                        <small class="text-muted">أدخل رابط فيديو يوتيوب كامل أو معرف الفيديو فقط (مثل: dQw4w9WgXcQ)</small>
                    </div>
                </div>
                
                <div class="border-top mt-4 pt-4">
                    <button type="submit" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-check-circle me-2"></i>حفظ التعديلات
                    </button>
                    <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary btn-lg me-2">إلغاء</a>
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
            </form>
        </div>
    </div>
</div>
@endsection
