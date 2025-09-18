@extends('layouts.layout')
@section('title')
    Lịch sử hoạt động
@endsection

@section('header')
    <style>
        .business-setting-container {
            transition: all 0.3s ease;
        }

        .business-setting-container:hover {
            transform: translateY(-2px);
        }

        .money-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15) !important;
        }
    </style>
@endsection

@section('content')
    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-4 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-2 flex-column justify-content-center my-0">
                    Lịch sử hoạt động
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Lịch sử hoạt động</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!-- Statistics Cards -->
            <div class="row g-5 mb-8">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Tổng hoạt động</div>
                                    <div class="fs-2x fw-bold text-success" id="totalActivities">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-activity fs-3x text-success opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Hôm nay</div>
                                    <div class="fs-2x fw-bold text-primary" id="todayActivities">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar-day fs-3x text-primary opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Tuần này</div>
                                    <div class="fs-2x fw-bold text-warning" id="weekActivities">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calendar-week fs-3x text-warning opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Dung lượng log</div>
                                    <div class="fs-2x fw-bold text-danger" id="logSize">0 MB</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-hdd fs-3x text-danger opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Activity Log Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-light-primary">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center w-100 gap-3">
                        <div class="flex-grow-1">
                            <h3 class="card-title text-primary fw-bold fs-3 mb-0">
                                Chi tiết hoạt động
                            </h3>
                            <p class="text-gray-600 mb-0 mt-2">Theo dõi tất cả các thay đổi trong hệ thống</p>
                        </div>
                        <div class="flex-shrink-0">
                            @can('activity-log-delete')
                                <button type="button" class="btn btn-primary" id="cleanupLogsBtn">
                                    <i class="bi bi-trash3 me-2"></i>
                                    Dọn dẹp log cũ
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search and Filters -->
                    <div class="row mb-6">
                        <div class="col-md-4">
                            <div class="position-relative">
                                <i
                                    class="bi bi-search fs-4 position-absolute top-50 start-0 translate-middle-y ms-4 text-gray-500"></i>
                                <input type="text" id="activitySearch" class="form-control form-control-lg ps-12"
                                    placeholder="Tìm kiếm hoạt động..." />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-lg" id="eventFilter">
                                <option value="">Tất cả sự kiện</option>
                                <option value="created">Tạo mới</option>
                                <option value="updated">Cập nhật</option>
                                <option value="deleted">Xóa</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control form-control-lg" id="dateFilter" />
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-light-primary btn-lg w-100" id="resetFilters">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Activity Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle fs-6" id="activityLogTable">
                            <thead class="table-primary">
                                <tr class="text-start fw-bold text-uppercase">
                                    <th class="text-center min-w-50px">STT</th>
                                    <th class="text-center min-w-125px">Người thực hiện</th>
                                    <th class="text-center min-w-100px">Sự kiện</th>
                                    <th class="min-w-200px">Chi tiết thay đổi</th>
                                    <th class="text-center min-w-150px">Thời gian</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-700">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let activityTable;

            // Initialize
            initActivityTable();
            loadStatistics();
            bindEvents();

            function formatNumber(number) {
                const str = number.toString();
                const formattedStr = str.replace(/(.{4})/g, '$1 ');
                return formattedStr.trim();
            }

            // Format time function
            function formatTime(time) {
                const dateTime = new Date(time);
                return dateTime.toLocaleString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }

            // Format key attributes
            function formatKeyAttributes(attributes) {
                const keyMapping = {
                    'id': 'ID',
                    'name': 'Tên',
                    'code': 'Mã',
                    'fee_percent': '% phí',
                    'account_name': 'Tên tài khoản',
                    'phone': 'Số điện thoại',
                    'card_number': 'Số thẻ',
                    'total_money': 'Tổng số tiền',
                    'formality': 'Hình thức',
                    'fee': 'Phí',
                    'pay_extra': 'Tiền trả thêm',
                    'bank_code': 'Ngân hàng',
                    'money': 'Số tiền',
                    'note': 'Ghi chú',
                    'key': 'Key',
                    'value': 'Giá trị',
                    'type': 'Loại',
                    'customer_id': 'ID khách hàng',
                    'account_number': 'Số tài khoản',
                    'login_info': 'Thông tin đăng nhập',
                    'date_due': 'Ngày đến hạn',
                    'date_return': 'Ngày trả',
                    'status': 'Trạng thái',
                    'month_expired': 'Tháng hết hạn',
                    'year_expired': 'Năm hết hạn',
                    'total_amount': 'Tổng số tiền',
                    'business_id': 'ID nghiệp vụ',
                    'machine_id': 'ID máy',
                    'is_money_checked': 'Đánh dấu số tiền',
                    'is_note_checked': 'Đánh dấu ghi chú',
                    'collaborator_id': 'ID Cộng tác viên',
                    'total_investment': 'Tổng đầu tư',
                    'business_setting_type': 'Loại cài đặt',
                    'business_setting_key': 'Key cài đặt',
                    'napas_fee_percent': '% Napas',
                    'visa_fee_percent': '% Visa',
                    'amex_fee_percent': '% Amex',
                    'master_fee_percent': '% Master',
                    'jcb_fee_percent': '% JCB',
                    'is_stranger': 'Khách lẻ',
                };

                return Object.fromEntries(
                    Object.entries(attributes)
                    .filter(([key]) => !['created_at', 'updated_at'].includes(key))
                    .map(([key, value]) => {
                        const displayKey = keyMapping[key] || key;

                        if (key === 'status') {
                            return [displayKey, value == 1 ? 'Hoạt động' : 'Không hoạt động'];
                        }

                        if (key === 'is_stranger') {
                            return [displayKey, value == 1 ? 'Có' : 'Không'];
                        }

                        if (key === 'fee' || key === 'total_money' || key === 'pay_extra' || key ===
                            'money') {
                            return [displayKey, value ? formatNumber(value) : ''];
                        }

                        return [displayKey, value ? value : ''];
                    })
                );
            }

            // Initialize DataTable
            function initActivityTable() {
                activityTable = $("#activityLogTable").DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    language: {
                        processing: "Đang tải dữ liệu...",
                        search: "Tìm kiếm:",
                        lengthMenu: "Hiển thị _MENU_ bản ghi",
                        info: "Hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
                        infoEmpty: "Hiển thị 0 đến 0 của 0 bản ghi",
                        infoFiltered: "(lọc từ _MAX_ bản ghi)",
                        paginate: {
                            first: "Đầu",
                            last: "Cuối",
                            next: "Tiếp",
                            previous: "Trước"
                        }
                    },
                    ajax: {
                        url: "{{ route('api.activity-log.list') }}",
                        type: "POST",
                        beforeSend: function(request) {
                            request.setRequestHeader("X-CSRF-TOKEN", token);
                        },
                        data: function(d) {
                            d.search = $('#activitySearch').val();
                            d.event = $('#eventFilter').val();
                            d.date = $('#dateFilter').val();
                        }
                    },
                    columnDefs: [{
                            targets: 0,
                            data: null,
                            orderable: false,
                            className: 'text-center',
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            targets: 1,
                            data: 'user',
                            orderable: false,
                            className: 'text-center',
                            render: function(data, type, row) {
                                return `
                                <span class="fw-semibold">${data?.name || 'N/A'}</span>
                        `;
                            }
                        },
                        {
                            targets: 2,
                            data: 'log',
                            orderable: false,
                            className: 'text-center',
                            render: function(data, type, row) {
                                const logData = JSON.parse(data);
                                const event = logData.event;

                                let badgeClass = 'bg-success text-white';
                                let eventText = 'Tạo mới';

                                if (event === 'updated') {
                                    badgeClass = 'bg-warning text-white';
                                    eventText = 'Cập nhật';
                                } else if (event === 'deleted') {
                                    badgeClass = 'bg-danger text-white';
                                    eventText = 'Xóa';
                                }

                                return `
                            <span class="badge ${badgeClass}">
                                ${eventText}
                            </span>
                                    `;
                            }
                        },
                        {
                            targets: 3,
                            data: 'log',
                            orderable: false,
                            render: function(data, type, row) {
                                const logData = JSON.parse(data);
                                const attributes = formatKeyAttributes(logData.attributes);
                                const changes = formatKeyAttributes(logData.changes);

                                let html = '<div class="p-3 bg-light rounded">';

                                if (Object.keys(attributes).length > 0) {
                                    html +=
                                        '<div class="mb-2"><strong class="text-muted">Thông tin:</strong></div>';
                                    html += '<ul class="mb-2 list-unstyled">';
                                    Object.entries(attributes).forEach(([key, value]) => {
                                        html +=
                                            `<li class="mb-1"><span class="text-muted">${key}:</span> <span class="fw-semibold">${value || 'N/A'}</span></li>`;
                                    });
                                    html += '</ul>';
                                }

                                if (Object.keys(changes).length > 0) {
                                    html +=
                                        '<div class="p-2 bg-primary-subtle border-start border-primary border-3">';
                                    html +=
                                        '<div class="mb-2"><strong class="text-primary"><i class="bi bi-arrow-right me-1"></i>Thay đổi thành:</strong></div>';
                                    html += '<ul class="mb-0 list-unstyled">';
                                    Object.entries(changes).forEach(([key, value]) => {
                                        html +=
                                            `<li class="mb-1"><span class="text-muted">${key}:</span> <span class="fw-semibold text-primary">${value}</span></li>`;
                                    });
                                    html += '</ul>';
                                    html += '</div>';
                                }

                                html += '</div>';
                                return html;
                            }
                        },
                        {
                            targets: 4,
                            data: 'created_at',
                            orderable: false,
                            className: 'text-center',
                            render: function(data, type, row) {
                                return `
                            <div class="d-flex flex-column align-items-center">
                                <span class="fw-bold text-gray-800">${formatTime(data)}</span>
                                <span class="text-muted fs-7">${moment(data).fromNow()}</span>
                            </div>
                        `;
                            }
                        }
                    ]
                });
            }

            // Load statistics
            async function loadStatistics() {
                try {
                    const response = await fetch("{{ route('api.activity-log.statistics') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        $('#totalActivities').text(data.total || 0);
                        $('#todayActivities').text(data.today || 0);
                        $('#weekActivities').text(data.week || 0);
                        $('#logSize').text((data.logSize || 0) + ' MB');
                    }
                } catch (error) {
                    console.error('Error loading statistics:', error);
                }
            }

            // Bind events
            function bindEvents() {
                // Search functionality
                $('#activitySearch').on('keyup', debounce(() => {
                    activityTable.ajax.reload();
                }, 500));

                // Filter changes
                $('#eventFilter, #dateFilter').on('change', () => {
                    activityTable.ajax.reload();
                });

                // Reset filters
                $('#resetFilters').on('click', () => {
                    $('#activitySearch').val('');
                    $('#eventFilter').val('');
                    $('#dateFilter').val('');
                    activityTable.ajax.reload();
                });

                // Cleanup logs
                $('#cleanupLogsBtn').on('click', handleLogCleanup);
            }

            // Handle log cleanup
            async function handleLogCleanup() {
                const result = await Swal.fire({
                    title: 'Xác nhận dọn dẹp log',
                    html: `
                <div class="text-start">
                    <p class="mb-3">Bạn có chắc chắn muốn xóa các log cũ hơn 30 ngày?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Lưu ý:</strong> Hành động này không thể hoàn tác!
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmCleanup">
                        <label class="form-check-label" for="confirmCleanup">
                            Tôi hiểu và muốn tiếp tục
                        </label>
                    </div>
                </div>
            `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Dọn dẹp ngay',
                    cancelButtonText: 'Hủy bỏ',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-light'
                    },
                    preConfirm: () => {
                        if (!document.getElementById('confirmCleanup').checked) {
                            Swal.showValidationMessage(
                                'Vui lòng xác nhận bằng cách tích vào ô kiểm tra');
                            return false;
                        }
                        return true;
                    }
                });

                if (result.isConfirmed) {
                    const loadingAlert = Swal.fire({
                        title: 'Đang xử lý...',
                        text: 'Vui lòng chờ trong giây lát',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const response = await fetch("{{ route('api.activity-log.cleanup') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (data.success) {
                            await Swal.fire({
                                title: 'Dọn dẹp thành công!',
                                html: `
                            <div class="text-center">
                                <i class="bi bi-check-circle text-success fs-3x mb-3"></i>
                                <p>Đã xóa <strong>${data.deleted_count}</strong> bản ghi log cũ</p>
                                <p class="text-muted">Giải phóng được <strong>${data.freed_space} MB</strong> dung lượng</p>
                            </div>
                        `,
                                icon: 'success',
                                confirmButtonText: 'Tuyệt vời!',
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });

                            activityTable.ajax.reload();
                            loadStatistics();
                        } else {
                            throw new Error(data.message || 'Có lỗi xảy ra');
                        }
                    } catch (error) {
                        await Swal.fire({
                            title: 'Lỗi!',
                            text: error.message || 'Không thể dọn dẹp log',
                            icon: 'error',
                            confirmButtonText: 'Đóng',
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                }
            }

            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
    </script>
@endsection
