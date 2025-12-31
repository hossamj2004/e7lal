@extends('layouts.dashboard')

@section('title', 'إدارة طلبات التبديل')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">طلبات التبديل</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.exchange-requests.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة طلب جديد
                        </a>
                        <a href="{{ route('admin.exchange-requests.export', request()->query()) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> تصدير
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['total'] }}</h3>
                                    <p>إجمالي الطلبات</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $stats['pending'] }}</h3>
                                    <p>في الانتظار</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ $stats['in_progress'] }}</h3>
                                    <p>قيد المعالجة</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-spinner"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $stats['completed'] }}</h3>
                                    <p>مكتملة</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body border-top">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">البحث</label>
                            <input type="text" name="search" class="form-control" placeholder="رقم الهاتف أو نوع السيارة" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select">
                                <option value="">الكل</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">المفضلة</label>
                            <select name="favorites" class="form-select">
                                <option value="">الكل</option>
                                <option value="1" {{ request('favorites') == '1' ? 'selected' : '' }}>المفضلة فقط</option>
                                <option value="0" {{ request('favorites') == '0' ? 'selected' : '' }}>غير المفضلة</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <a href="{{ route('admin.exchange-requests.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times"></i> إعادة تعيين
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Exchange Requests Table -->
                <div class="card-body table-responsive p-0">
                    <table id="exchangeRequestsTable" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>العميل</th>
                                <th>رقم الهاتف</th>
                                <th>نوع السيارة</th>
                                <th>السعر</th>
                                <th>الموقع</th>
                                <th>الحالة</th>
                                <th>المفضلة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exchangeRequests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>
                                        @if($request->user)
                                            <strong>{{ $request->user->name }}</strong>
                                            <br><small class="text-muted">مسجل</small>
                                        @else
                                            <span class="text-muted">زائر</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="tel:{{ $request->phone }}" class="text-decoration-none">
                                            {{ $request->phone }}
                                        </a>
                                        <br>
                                        <a href="https://wa.me/{{ $request->phone }}" target="_blank" class="btn btn-success btn-sm">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </td>
                                    <td>{{ $request->car_model }}</td>
                                    <td>{{ $request->formatted_price }}</td>
                                    <td>{{ $request->location }}</td>
                                    <td>
                                        {!! $request->status_badge !!}
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm toggle-favorite {{ $request->is_favorite ? 'btn-warning' : 'btn-outline-warning' }}"
                                                data-id="{{ $request->id }}"
                                                data-favorite="{{ $request->is_favorite }}">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.exchange-requests.show', $request) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-warning btn-sm edit-status" data-id="{{ $request->id }}" data-status="{{ $request->status }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.exchange-requests.destroy', $request) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا توجد طلبات تبديل</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($exchangeRequests->hasPages())
                    <div class="card-footer">
                        {{ $exchangeRequests->withQueryString()->links() }}
                    </div>
                @endif
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
                        <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="أضف ملاحظاتك هنا..."></textarea>
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
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 1rem;
        position: relative;
        display: block;
        margin-bottom: .5rem;
        box-shadow: 0 1px 1px rgba(0,0,0,.1);
    }
    .small-box > .inner {
        padding: 10px;
    }
    .small-box > .small-box-footer {
        position: relative;
        text-align: center;
        padding: 3px 0;
        color: rgba(255,255,255,.8);
        display: block;
        z-index: 10;
        background: rgba(0,0,0,.1);
        text-decoration: none;
    }
    .small-box > .small-box-footer:hover {
        color: #fff;
        background: rgba(0,0,0,.15);
    }
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
    }
    .small-box p {
        font-size: 1rem;
        margin: 0;
    }
    .small-box > .icon {
        color: rgba(0,0,0,.15);
        z-index: 0;
    }
    .small-box > .icon > i {
        font-size: 70px;
        position: absolute;
        right: 15px;
        top: 15px;
        transition: all .3s linear;
    }
    .small-box:hover {
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,.2);
    }
    .small-box:hover > .icon > i {
        transform: scale(1.1);
    }
    .bg-info { background-color: #17a2b8 !important; }
    .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
    .bg-primary { background-color: #007bff !important; }
    .bg-success { background-color: #28a745 !important; }
</style>
@endpush


<script>
$(document).ready(function() {
    // Debug: Log when any link or button in the table is clicked
    $('#exchangeRequestsTable').on('click', 'a, button', function(e) {
        console.log('Clicked element:', this);
        console.log('Element tag:', this.tagName);
        console.log('Element href/action:', $(this).attr('href') || $(this).attr('action'));
        console.log('Event:', e);
    });
    // Toggle favorite
    $('.toggle-favorite').on('click', function() {
        const button = $(this);
        const requestId = button.data('id');
        const isFavorite = button.data('favorite');
        const url = `{{ route('admin.exchange-requests.toggle-favorite', ':id') }}`.replace(':id', requestId);

        console.log('Index Favorite toggle URL:', url); // Debug

        $.post(url, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            console.log('Index Favorite response:', response); // Debug
            if (response.success) {
                button.toggleClass('btn-warning btn-outline-warning');
                button.data('favorite', response.is_favorite);

                // Show success message
                toastr.success(response.message);
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Index Favorite toggle error:', xhr, status, error); // Debug
            toastr.error('حدث خطأ أثناء تحديث المفضلة');
        });
    });

    // Edit status modal
    $('.edit-status').on('click', function() {
        const requestId = $(this).data('id');
        const currentStatus = $(this).data('status');

        console.log('Edit status clicked for request:', requestId, 'current status:', currentStatus);

        $('#status').val(currentStatus);
        $('#editStatusModal').modal('show');

        // Update form action - use proper route helper
        const updateUrl = `{{ route('admin.exchange-requests.update', ':id') }}`.replace(':id', requestId);
        $('#statusForm').attr('action', updateUrl);

        console.log('Form action set to:', updateUrl);
    });

    // Submit status form
    $('#statusForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const actionUrl = $(this).attr('action');

        console.log('Status form submitted');
        console.log('Action URL:', actionUrl);
        console.log('Form data:', Object.fromEntries(formData));

        // Add method spoofing for PUT request
        formData.append('_method', 'PUT');

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        })
        .done(function(response) {
            console.log('Status update response:', response);
            if (response.success) {
                toastr.success(response.message || 'تم تحديث حالة الطلب بنجاح');
                $('#editStatusModal').modal('hide');
                location.reload();
            } else {
                alert('فشل في تحديث الحالة: ' + JSON.stringify(response));
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Status update error:', xhr, status, error);
            console.error('Response text:', xhr.responseText);
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
                alert('حدث خطأ أثناء حفظ التغييرات: ' + error);
            }
        });
    });
});
</script>

@endsection
