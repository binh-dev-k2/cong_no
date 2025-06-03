@extends('layouts.layout')
@section('title')
    Thống kê lợi nhuận đại lý
@endsection

@section('header')
    <style>
        .hover-lift {
            transition: transform 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }
    </style>
@endsection

@section('content')
    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-4 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-2 flex-column justify-content-center my-0">
                    Thống kê lợi nhuận đại lý
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Thống kê lợi nhuận đại lý</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!-- Statistics Cards -->
            {{-- <div class="row g-5 mb-8">
                <div class="col-md-3">
                    <div class="card shadow-sm hover-lift">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Tổng lợi nhuận</div>
                                    <div class="fs-2x fw-bold text-primary" id="totalProfit">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-graph-up-arrow fs-3x text-primary opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm hover-lift">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Tổng nghiệp vụ</div>
                                    <div class="fs-2x fw-bold text-success" id="totalBusinesses">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-briefcase fs-3x text-success opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm hover-lift">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Tổng đại lý</div>
                                    <div class="fs-2x fw-bold text-warning" id="totalAgencies">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-building fs-3x text-warning opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm hover-lift">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Lợi nhuận trung bình</div>
                                    <div class="fs-2x fw-bold text-danger" id="averageProfit">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-calculator fs-3x text-danger opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Filters -->
            <div class="card shadow-sm mb-8">
                <div class="card-body">
                    <form id="filterForm" class="row g-4">
                        <div class="col-md-3">
                            <label class="form-label">Đại lý</label>
                            <select class="form-select" id="agencyFilter">
                                <option value="">Tất cả đại lý</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" id="startDate">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>
                                Lọc kết quả
                            </button>
                            <button type="reset" class="btn btn-light ms-2">
                                <i class="bi bi-x-circle me-2"></i>
                                Xóa bộ lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-light-primary">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center w-100 gap-3">
                        <div class="flex-grow-1">
                            <h3 class="card-title text-primary fw-bold fs-3 mb-0">
                                Thống kê lợi nhuận
                            </h3>
                            <p class="text-gray-600 mb-0 mt-2">Quản lý thông tin thống kê lợi nhuận</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="agencyAnalyticsTable">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Đại lý</th>
                                    <th class="text-center">Nghiệp vụ hoàn thành</th>
                                    <th class="text-center">Nghiệp vụ chưa hoàn thành</th>
                                    <th class="text-center">Tổng tiền</th>
                                    <th class="text-center">Lợi nhuận</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded dynamically -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-center">Tổng</td>
                                    <td id="totalMoney" class="text-center text-primary fw-bold"></td>
                                    <td id="totalProfit" class="text-center text-primary fw-bold"></td>
                                </tr>
                            </tfoot>
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

            const formatter = new Intl.NumberFormat('vi-VN', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });

            function formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('vi-VN', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            const datatable = $("#agencyAnalyticsTable").DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('api.agency-analytics.list') }}",
                    type: "POST",
                    beforeSend: function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", token);
                    },
                    data: function(d) {
                        d.agency_id = $('#agencyFilter').val();
                        d.start_date = $('#startDate').val();
                        d.end_date = $('#endDate').val();
                    },
                },
                columnDefs: [{
                        targets: 0,
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return `<span>${meta.row + meta.settings._iDisplayStart + 1}</span>`
                        }
                    },
                    {
                        targets: 1,
                        data: 'name',
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<span class="fw-bold text-primary fs-5 mb-1">${data ?? ''}</span>`
                        }
                    },
                    {
                        targets: 2,
                        data: 'completed_businesses_count',
                        orderable: false,
                        className: 'text-center min-w-50px',
                    },
                    {
                        targets: 3,
                        data: 'uncompleted_businesses_count',
                        orderable: false,
                        className: 'text-center min-w-50px',
                    },
                    {
                        targets: 4,
                        data: 'agency_businesses_sum_total_money',
                        orderable: false,
                        className: 'text-center min-w-50px',
                        render: function(data, type, row) {
                            return data !== null ? formatter.format(data) : '0';
                        }
                    },
                    {
                        targets: 5,
                        data: 'agency_businesses_sum_profit',
                        orderable: false,
                        className: 'text-center min-w-50px',
                        render: function(data, type, row) {
                            return data !== null ? formatter.format(data) : '0';
                        }
                    },
                ]
            });

            datatable.on('xhr.dt', function(e, settings, json, xhr) {
                $('#totalProfit').text(formatter.format(json.total_profit ?? 0));
                $('#totalMoney').text(formatter.format(json.total_money ?? 0));
            });

            const getAgencies = async () => {
                const response = await axios.get("{{ route('api.agency.list') }}");
                const agencies = response.data.data;
                const select = $('#agencyFilter');
                agencies.forEach(agency => {
                    select.append(`<option value="${agency.id}">${agency.name}</option>`);
                });
            }

            getAgencies();

            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                datatable.ajax.reload();
            });

            $('#resetFilter').on('click', function(e) {
                e.preventDefault();
                $('#filterForm')[0].reset();
                datatable.ajax.reload();
            });
        });
    </script>
@endsection
