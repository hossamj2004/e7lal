@extends('layouts.dashboard')

@section('title', 'تفاصيل طلب التبديل #' . $exchangeRequest->id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">طلب التبديل #{{ $exchangeRequest->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.exchange-requests.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> العودة
                        </a>
                        <button type="button" class="btn btn-warning btn-sm edit-status" data-id="{{ $exchangeRequest->id }}" data-status="{{ $exchangeRequest->status }}">
                            <i class="fas fa-edit"></i> تحديث الحالة
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">العميل</span>
                                    <span class="info-box-number">
                                        @if($exchangeRequest->user)
                                            <strong>{{ $exchangeRequest->user->name }}</strong>
                                            <br><small class="text-muted">{{ $exchangeRequest->user->email }}</small>
                                        @else
                                            <span class="text-muted">زائر غير مسجل</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-phone"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">رقم الهاتف</span>
                                    <span class="info-box-number">
                                        <a href="tel:{{ $exchangeRequest->phone }}" class="text-decoration-none">
                                            {{ $exchangeRequest->phone }}
                                        </a>
                                        <br>
                                        <a href="https://wa.me/{{ $exchangeRequest->phone }}" target="_blank" class="btn btn-success btn-sm mt-1">
                                            <i class="fab fa-whatsapp"></i> واتساب
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-car"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">نوع السيارة</span>
                                    <span class="info-box-number">{{ $exchangeRequest->car_model }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">السعر المطلوب</span>
                                    <span class="info-box-number">{{ $exchangeRequest->formatted_price }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="fas fa-map-marker-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الموقع</span>
                                    <span class="info-box-number">{{ $exchangeRequest->location }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-info-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الحالة</span>
                                    <span class="info-box-number">{!! $exchangeRequest->status_badge !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($exchangeRequest->desired_price_range)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الرينج السعري المطلوب</span>
                                    <span class="info-box-number">{{ $exchangeRequest->desired_price_range }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($exchangeRequest->ad_link)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-link"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">رابط الإعلان</span>
                                    <span class="info-box-number">
                                        <a href="{{ $exchangeRequest->ad_link }}" target="_blank" class="text-decoration-none">
                                            {{ $exchangeRequest->ad_link }}
                                            <i class="fas fa-external-link-alt ml-1"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-sticky-note"></i> ملاحظات المدير
                                        <button type="button" class="btn btn-sm btn-outline-primary float-end" onclick="$('#notesForm').toggle()">
                                            <i class="fas fa-edit"></i> تعديل
                                        </button>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($exchangeRequest->admin_notes)
                                        <p class="mb-0">{{ $exchangeRequest->admin_notes }}</p>
                                    @else
                                        <p class="text-muted mb-0">لا توجد ملاحظات</p>
                                    @endif

                                    <form id="notesForm" style="display: none;" class="mt-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <textarea name="admin_notes" id="notes_admin_notes" class="form-control" rows="3" placeholder="أضف ملاحظاتك هنا...">{{ $exchangeRequest->admin_notes }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">حفظ الملاحظات</button>
                                        <button type="button" class="btn btn-secondary" onclick="$('#notesForm').hide()">إلغاء</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">الإجراءات السريعة</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button"
                                class="btn toggle-favorite {{ $exchangeRequest->is_favorite ? 'btn-warning' : 'btn-outline-warning' }}"
                                data-id="{{ $exchangeRequest->id }}"
                                data-favorite="{{ $exchangeRequest->is_favorite }}">
                            <i class="fas fa-star"></i>
                            {{ $exchangeRequest->is_favorite ? 'حذف من المفضلة' : 'إضافة للمفضلة' }}
                        </button>

                        @if($exchangeRequest->isPending())
                            <button type="button" class="btn btn-info edit-status" data-id="{{ $exchangeRequest->id }}" data-status="in_progress">
                                <i class="fas fa-play"></i> بدء المعالجة
                            </button>
                        @endif

                        @if($exchangeRequest->isInProgress())
                            <button type="button" class="btn btn-success edit-status" data-id="{{ $exchangeRequest->id }}" data-status="completed">
                                <i class="fas fa-check"></i> تم الانتهاء
                            </button>
                        @endif

                        <button type="button" class="btn btn-secondary edit-status" data-id="{{ $exchangeRequest->id }}" data-status="{{ $exchangeRequest->status }}">
                            <i class="fas fa-edit"></i> تغيير الحالة
                        </button>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">الوقت الزمني</h5>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-inverse">
                        <div class="time-label">
                            <span class="bg-primary">{{ $exchangeRequest->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-plus bg-primary"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $exchangeRequest->created_at->format('H:i') }}</span>
                                <h3 class="timeline-header">تم إنشاء الطلب</h3>
                                <div class="timeline-body">
                                    تم استلام طلب التبديل من {{ $exchangeRequest->user ? $exchangeRequest->user->name : 'زائر' }}
                                </div>
                            </div>
                        </div>

                        @if($exchangeRequest->responded_at)
                        <div class="time-label">
                            <span class="bg-info">{{ $exchangeRequest->responded_at->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-reply bg-info"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $exchangeRequest->responded_at->format('H:i') }}</span>
                                <h3 class="timeline-header">تم الرد على الطلب</h3>
                                <div class="timeline-body">
                                    بدأت معالجة طلب التبديل
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($exchangeRequest->isCompleted())
                        <div class="time-label">
                            <span class="bg-success">{{ $exchangeRequest->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-check bg-success"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $exchangeRequest->updated_at->format('H:i') }}</span>
                                <h3 class="timeline-header">تم إكمال الطلب</h3>
                                <div class="timeline-body">
                                    تم الانتهاء من معالجة طلب التبديل
                                </div>
                            </div>
                        </div>
                        @endif

                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Status Modal -->
<div class="modal fade" id="editStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تحديث حالة الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending">في الانتظار</option>
                            <option value="in_progress">قيد المعالجة</option>
                            <option value="completed">مكتملة</option>
                            <option value="cancelled">ملغية</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">ملاحظات المدير</label>
                        <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="أضف ملاحظاتك هنا...">{{ $exchangeRequest->admin_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .info-box {
        box-shadow: 0 0 1px rgba(0,123,255,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: .25rem;
        margin-bottom: 1rem;
        background-color: #fff;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .info-box-icon {
        border-radius: .25rem 0 0 .25rem;
        align-items: center;
        display: flex;
        font-size: 1.875rem;
        justify-content: center;
        text-align: center;
        width: 70px;
        height: 70px;
    }
    .info-box-content {
        padding: 5px 10px;
        margin-left: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .info-box-text {
        text-transform: uppercase;
        font-weight: 700;
        font-size: .6875rem;
        color: #6c757d;
    }
    .info-box-number {
        font-size: 1.125rem;
        font-weight: 700;
        color: #212529;
    }
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }
    .timeline > li {
        position: relative;
        margin-right: 10px;
        margin-bottom: 15px;
    }
    .timeline > li > .timeline-item {
        box-shadow: 0 1px 1px rgba(0,0,0,.1);
        border-radius: .25rem;
        margin-top: 0;
        margin-right: 0;
        margin-left: 60px;
        background: #fff;
        color: #444;
        margin-left: 60px;
        margin-right: 0;
        margin-top: 0;
        padding: 0;
        position: relative;
    }
    .timeline > li > .timeline-item > .time {
        color: #999;
        float: right;
        padding: 10px;
        font-size: 12px;
    }
    .timeline > li > .timeline-item > .timeline-header {
        margin: 0;
        color: #555;
        border-bottom: 1px solid #f4f4f4;
        padding: 10px;
        font-size: 16px;
        line-height: 1.1;
        font-weight: 600;
    }
    .timeline > li > .timeline-item > .timeline-body {
        padding: 10px;
    }
    .timeline > li > .fa, .timeline > li > .fas, .timeline > li > .far {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #666;
        background: #d2d6de;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }
    .timeline > .time-label > span {
        font-weight: 600;
        padding: 5px;
        display: inline-block;
        background-color: #fff;
        border-radius: 4px;
    }
    .bg-primary { background-color: #007bff !important; }
    .bg-info { background-color: #17a2b8 !important; }
    .bg-success { background-color: #28a745 !important; }
    .bg-gray { background-color: #6c757d !important; }
</style>
@endpush


<script>
$(document).ready(function() {
    // Debug: Log when any button is clicked
    $('button').on('click', function(e) {
        console.log('Button clicked:', this);
        console.log('Button classes:', $(this).attr('class'));
        console.log('Button data:', {
            id: $(this).data('id'),
            status: $(this).data('status'),
            favorite: $(this).data('favorite')
        });
    });
    // Toggle favorite
    $('.toggle-favorite').on('click', function() {
        const button = $(this);
        const requestId = button.data('id');
        const url = `{{ route('admin.exchange-requests.toggle-favorite', ':id') }}`.replace(':id', requestId);

        console.log('Favorite toggle URL:', url); // Debug

        $.post(url, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            console.log('Favorite response:', response); // Debug
            if (response.success) {
                button.toggleClass('btn-warning btn-outline-warning');
                button.data('favorite', response.is_favorite);
                button.html(`<i class="fas fa-star"></i> ${response.is_favorite ? 'حذف من المفضلة' : 'إضافة للمفضلة'}`);

                toastr.success(response.message);
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Favorite toggle error:', xhr, status, error); // Debug
            toastr.error('حدث خطأ أثناء تحديث المفضلة');
        });
    });

    // Edit status modal
    $('.edit-status').on('click', function() {
        const requestId = $(this).data('id');
        const currentStatus = $(this).data('status');

        $('#status').val(currentStatus);
        $('#editStatusModal').modal('show');

        // Update form action
        $('#statusForm').attr('action', `{{ url('admin/exchange-requests') }}/${requestId}`);
    });

    // Submit status form
    $('#statusForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        })
        .done(function(response) {
            if (response.success) {
                $('#editStatusModal').modal('hide');
                location.reload();
            }
        })
        .fail(function(xhr) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                let errorText = '';
                Object.values(errors).forEach(errorArray => {
                    errorArray.forEach(error => {
                        errorText += error + '\n';
                    });
                });
                alert('خطأ في البيانات:\n' + errorText);
            } else {
                alert('حدث خطأ أثناء حفظ التغييرات');
            }
        });
    });

    // Submit notes form
    $('#notesForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const url = '{{ route("admin.exchange-requests.update", $exchangeRequest) }}';

        // Manually add CSRF token to FormData
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message || 'تم حفظ الملاحظات بنجاح');
                location.reload();
            }
        })
        .fail(function(xhr) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                let errorText = '';
                Object.values(errors).forEach(errorArray => {
                    errorArray.forEach(error => {
                        errorText += error + '\n';
                    });
                });
                alert('خطأ في البيانات:\n' + errorText);
            } else {
                alert('حدث خطأ أثناء حفظ الملاحظات');
            }
        });
    });
});
</script>

@endsection
