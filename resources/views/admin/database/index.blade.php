@extends('layouts.dashboard')

@section('title', 'إدارة قاعدة البيانات - E7lal.com')
@section('page-title', 'إدارة قاعدة البيانات')

@section('styles')
<style>
    .query-result {
        max-height: 400px;
        overflow-y: auto;
    }

    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
    }

    .command-output {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        font-family: 'Courier New', monospace;
        white-space: pre-wrap;
        max-height: 300px;
        overflow-y: auto;
    }

    .execution-time {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .query-history {
        max-height: 200px;
        overflow-y: auto;
    }

    .tab-content {
        padding-top: 20px;
    }

    .database-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="database-info">
    <h4><i class="bi bi-database me-2"></i>إدارة قاعدة البيانات</h4>
    <p class="mb-0">أداة لتنفيذ استعلامات SQL وأوامر Artisan بشكل آمن</p>
</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs" id="databaseTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="sql-tab" data-bs-toggle="tab" data-bs-target="#sql" type="button" role="tab">
            <i class="bi bi-code-slash me-1"></i>استعلامات SQL
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="commands-tab" data-bs-toggle="tab" data-bs-target="#commands" type="button" role="tab">
            <i class="bi bi-terminal me-1"></i>أوامر Artisan
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tables-tab" data-bs-toggle="tab" data-bs-target="#tables" type="button" role="tab">
            <i class="bi bi-table me-1"></i>الجداول
        </button>
    </li>
</ul>

<div class="tab-content" id="databaseTabsContent">
    <!-- SQL Queries Tab -->
    <div class="tab-pane fade show active" id="sql" role="tabpanel">
        <div class="row">
            <div class="col-lg-8">
                <div class="stat-card">
                    <h5 class="mb-3"><i class="bi bi-code-slash me-2"></i>تنفيذ استعلام SQL</h5>

                    <form id="sqlForm">
                        @csrf
                        <div class="mb-3">
                            <label for="query" class="form-label">الاستعلام SQL:</label>
                            <textarea
                                class="form-control"
                                id="query"
                                name="query"
                                rows="6"
                                placeholder="SELECT * FROM users LIMIT 10;"
                                required
                            ></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmQuery" required>
                                <label class="form-check-label" for="confirmQuery">
                                    أؤكد أنني أريد تنفيذ هذا الاستعلام
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom" id="runQueryBtn">
                            <i class="bi bi-play-fill me-2"></i>تنفيذ الاستعلام
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="stat-card">
                    <h6 class="mb-3"><i class="bi bi-clock-history me-2"></i>استعلامات شائعة</h6>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm text-start query-template" data-query="SELECT * FROM users LIMIT 10;">
                            عرض أول 10 مستخدمين
                        </button>
                        <button class="btn btn-outline-primary btn-sm text-start query-template" data-query="SELECT COUNT(*) as total FROM users;">
                            عدد المستخدمين
                        </button>
                        <button class="btn btn-outline-primary btn-sm text-start query-template" data-query="SELECT * FROM cars WHERE status = 'available' LIMIT 5;">
                            السيارات المتاحة
                        </button>
                        <button class="btn btn-outline-primary btn-sm text-start query-template" data-query="SHOW TABLES;">
                            عرض الجداول
                        </button>
                        <button class="btn btn-outline-danger btn-sm text-start query-template" data-query="SELECT table_name, table_rows FROM information_schema.tables WHERE table_schema = DATABASE() ORDER BY table_rows DESC;">
                            حجم الجداول
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Query Results -->
        <div class="stat-card mt-3" id="queryResults" style="display: none;">
            <h6 class="mb-3">
                <i class="bi bi-table me-2"></i>نتائج الاستعلام
                <span class="execution-time ms-2" id="executionTime"></span>
            </h6>

            <div id="resultsContent"></div>
        </div>
    </div>

    <!-- Commands Tab -->
    <div class="tab-pane fade" id="commands" role="tabpanel">
        <div class="row">
            <div class="col-lg-8">
                <div class="stat-card">
                    <h5 class="mb-3"><i class="bi bi-terminal me-2"></i>تنفيذ أمر Artisan</h5>

                    <form id="commandForm">
                        @csrf
                        <div class="mb-3">
                            <label for="command" class="form-label">الأمر:</label>
                            <input
                                type="text"
                                class="form-control"
                                id="command"
                                name="command"
                                placeholder="php artisan migrate"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmCommand" required>
                                <label class="form-check-label" for="confirmCommand">
                                    أؤكد أنني أريد تنفيذ هذا الأمر
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom" id="runCommandBtn">
                            <i class="bi bi-play-fill me-2"></i>تنفيذ الأمر
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="stat-card">
                    <h6 class="mb-3"><i class="bi bi-lightbulb me-2"></i>أوامر شائعة</h6>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-success btn-sm text-start command-template" data-command="php artisan migrate">
                            تشغيل الهجرات
                        </button>
                        <button class="btn btn-outline-success btn-sm text-start command-template" data-command="php artisan db:seed">
                            تشغيل البذور
                        </button>
                        <button class="btn btn-outline-info btn-sm text-start command-template" data-command="php artisan cache:clear">
                            مسح الذاكرة المؤقتة
                        </button>
                        <button class="btn btn-outline-info btn-sm text-start command-template" data-command="php artisan config:clear">
                            مسح التكوين
                        </button>
                        <button class="btn btn-outline-warning btn-sm text-start command-template" data-command="php artisan queue:work --once">
                            تشغيل قائمة الانتظار
                        </button>
                        <button class="btn btn-outline-danger btn-sm text-start command-template" data-command="php artisan migrate:rollback">
                            التراجع عن الهجرة
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Command Output -->
        <div class="stat-card mt-3" id="commandOutput" style="display: none;">
            <h6 class="mb-3">
                <i class="bi bi-terminal me-2"></i>مخرجات الأمر
                <span class="execution-time ms-2" id="commandExecutionTime"></span>
            </h6>

            <div class="command-output" id="commandContent"></div>
        </div>
    </div>

    <!-- Tables Tab -->
    <div class="tab-pane fade" id="tables" role="tabpanel">
        <div class="stat-card">
            <h5 class="mb-3"><i class="bi bi-table me-2"></i>جداول قاعدة البيانات</h5>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">اختر جدول:</label>
                        <select class="form-select" id="tableSelect">
                            <option value="">جاري التحميل...</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="tableStructure" style="display: none;">
                <h6 class="mb-3">هيكل الجدول: <span id="tableName"></span></h6>

                <div class="row">
                    <div class="col-md-6">
                        <h6>الأعمدة</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-sm" id="columnsTable">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>النوع</th>
                                        <th>Null</th>
                                        <th>المفتاح</th>
                                        <th>الافتراضي</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6>الفهارس</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-sm" id="indexesTable">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>العمود</th>
                                        <th>فريد</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Load tables on page load
    loadTables();

    // SQL Query Form
    $('#sqlForm').on('submit', function(e) {
        e.preventDefault();

        const query = $('#query').val().trim();
        if (!query) {
            alert('يرجى إدخال استعلام SQL');
            return;
        }

        $('#runQueryBtn').prop('disabled', true).html('<i class="bi bi-hourglass me-2"></i>جاري التنفيذ...');

        $.ajax({
            url: '{{ route("admin.database.run-query") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#queryResults').show();
                $('#executionTime').text(`(${response.execution_time}ms)`);

                if (response.success) {
                    displayQueryResults(response.results, response.query_type);
                } else {
                    $('#resultsContent').html(`<div class="alert alert-danger">${response.error}</div>`);
                }
            },
            error: function(xhr) {
                $('#resultsContent').html(`<div class="alert alert-danger">خطأ في الطلب: ${xhr.responseText}</div>`);
            },
            complete: function() {
                $('#runQueryBtn').prop('disabled', false).html('<i class="bi bi-play-fill me-2"></i>تنفيذ الاستعلام');
                $('#confirmQuery').prop('checked', false);
            }
        });
    });

    // Command Form
    $('#commandForm').on('submit', function(e) {
        e.preventDefault();

        const command = $('#command').val().trim();
        if (!command) {
            alert('يرجى إدخال أمر');
            return;
        }

        $('#runCommandBtn').prop('disabled', true).html('<i class="bi bi-hourglass me-2"></i>جاري التنفيذ...');

        $.ajax({
            url: '{{ route("admin.database.run-command") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#commandOutput').show();
                $('#commandExecutionTime').text(`(${response.execution_time}ms)`);

                let outputClass = response.success ? 'alert-success' : 'alert-danger';
                $('#commandContent').html(`<div class="alert ${outputClass}"><pre>${response.output}</pre></div>`);
            },
            error: function(xhr) {
                $('#commandContent').html(`<div class="alert alert-danger">خطأ في الطلب: ${xhr.responseText}</div>`);
            },
            complete: function() {
                $('#runCommandBtn').prop('disabled', false).html('<i class="bi bi-play-fill me-2"></i>تنفيذ الأمر');
                $('#confirmCommand').prop('checked', false);
            }
        });
    });

    // Query Templates
    $('.query-template').on('click', function() {
        $('#query').val($(this).data('query'));
        $('#sql-tab').tab('show');
    });

    // Command Templates
    $('.command-template').on('click', function() {
        $('#command').val($(this).data('command'));
        $('#commands-tab').tab('show');
    });

    // Table Selection
    $('#tableSelect').on('change', function() {
        const tableName = $(this).val();
        if (tableName) {
            loadTableStructure(tableName);
        } else {
            $('#tableStructure').hide();
        }
    });
});

function loadTables() {
    $.ajax({
        url: '{{ route("admin.database.tables") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">اختر جدول...</option>';
                response.tables.forEach(table => {
                    options += `<option value="${table}">${table}</option>`;
                });
                $('#tableSelect').html(options);
            } else {
                $('#tableSelect').html('<option value="">خطأ في تحميل الجداول</option>');
            }
        },
        error: function() {
            $('#tableSelect').html('<option value="">خطأ في تحميل الجداول</option>');
        }
    });
}

function loadTableStructure(tableName) {
    $.ajax({
        url: '{{ route("admin.database.table-structure") }}',
        method: 'GET',
        data: { table: tableName },
        success: function(response) {
            if (response.success) {
                $('#tableName').text(tableName);
                displayColumns(response.columns);
                displayIndexes(response.indexes);
                $('#tableStructure').show();
            } else {
                alert('خطأ في تحميل هيكل الجدول: ' + response.error);
            }
        },
        error: function(xhr) {
            alert('خطأ في الطلب: ' + xhr.responseText);
        }
    });
}

function displayColumns(columns) {
    let html = '';
    columns.forEach(column => {
        html += `
            <tr>
                <td>${column.Field}</td>
                <td>${column.Type}</td>
                <td>${column.Null === 'YES' ? 'نعم' : 'لا'}</td>
                <td>${column.Key || '-'}</td>
                <td>${column.Default || '-'}</td>
            </tr>
        `;
    });
    $('#columnsTable tbody').html(html);
}

function displayIndexes(indexes) {
    let html = '';
    indexes.forEach(index => {
        html += `
            <tr>
                <td>${index.Key_name}</td>
                <td>${index.Column_name}</td>
                <td>${index.Non_unique == 0 ? 'نعم' : 'لا'}</td>
            </tr>
        `;
    });
    $('#indexesTable tbody').html(html);
}

function displayQueryResults(results, queryType) {
    if (queryType === 'SELECT') {
        if (results.length === 0) {
            $('#resultsContent').html('<div class="alert alert-info">لا توجد نتائج</div>');
            return;
        }

        let html = '<div class="table-responsive"><table class="table table-striped table-sm"><thead><tr>';

        // Get column names from first result
        Object.keys(results[0]).forEach(key => {
            html += `<th>${key}</th>`;
        });

        html += '</tr></thead><tbody>';

        results.forEach(row => {
            html += '<tr>';
            Object.values(row).forEach(value => {
                html += `<td>${value !== null ? value : '<em>null</em>'}</td>`;
            });
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        html += `<div class="mt-2 text-muted">عدد النتائج: ${results.length}</div>`;

        $('#resultsContent').html(html);
    } else {
        // Non-SELECT query results
        let html = '<div class="alert alert-success">';
        html += '<h6>تم تنفيذ الاستعلام بنجاح</h6>';
        if (results.affected_rows !== undefined) {
            html += `<p class="mb-0">عدد الصفوف المتأثرة: <strong>${results.affected_rows}</strong></p>`;
        }
        if (results.message) {
            html += `<p class="mb-0">${results.message}</p>`;
        }
        html += '</div>';

        $('#resultsContent').html(html);
    }
}
</script>
@endsection

