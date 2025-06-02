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
            <div class="row g-5 mb-8">
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
            </div>

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
                            <label class="form-label">Máy</label>
                            <select class="form-select" id="machineFilter">
                                <option value="">Tất cả máy</option>
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
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="profitTable">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Đại lý</th>
                                    <th class="text-center">Máy</th>
                                    <th class="text-center">Tổng tiền</th>
                                    <th class="text-center">Phí đại lý</th>
                                    <th class="text-center">Phí máy</th>
                                    <th class="text-center">Lợi nhuận</th>
                                    <th class="text-center">Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị <span id="startRecord">0</span> - <span id="endRecord">0</span> của <span id="totalRecords">0</span> bản ghi
                        </div>
                        <div class="pagination-container">
                            <!-- Pagination will be loaded dynamically -->
                        </div>
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
            let currentPage = 1;
            const perPage = 10;

            // Setup axios defaults
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
            axios.defaults.headers.common['Accept'] = 'application/json';
            axios.defaults.headers.common['Content-Type'] = 'application/json';

            // Initialize
            init();

            async function init() {
                await Promise.all([
                    loadAgencies(),
                    loadMachines(),
                    loadProfitData()
                ]);
                bindEvents();
            }

            // Load agencies for filter
            async function loadAgencies() {
                try {
                    const response = await axios.get('/api/agency/list');
                    if (response.data.code === 0) {
                        const agencies = response.data.data;
                        const select = $('#agencyFilter');
                        agencies.forEach(agency => {
                            select.append(`<option value="${agency.id}">${agency.name}</option>`);
                        });
                    }
                } catch (error) {
                    console.error('Error loading agencies:', error);
                }
            }

            // Load machines for filter
            async function loadMachines() {
                try {
                    const response = await axios.get('/api/agency/machines');
                    if (response.data.code === 0) {
                        const machines = response.data.data;
                        const select = $('#machineFilter');
                        machines.forEach(machine => {
                            select.append(`<option value="${machine.id}">${machine.name}</option>`);
                        });
                    }
                } catch (error) {
                    console.error('Error loading machines:', error);
                }
            }

            // Load profit data
            async function loadProfitData() {
                showMainLoading(true);

                try {
                    const filters = {
                        agency_id: $('#agencyFilter').val(),
                        machine_id: $('#machineFilter').val(),
                        start_date: $('#startDate').val(),
                        end_date: $('#endDate').val(),
                        page: currentPage,
                        per_page: perPage
                    };

                    const response = await axios.get('/api/agency/profit-analytics', { params: filters });

                    if (response.data.code === 0) {
                        const data = response.data.data;
                        renderProfitTable(data.items);
                        updatePagination(data.pagination);
                        updateStatistics(data.statistics);
                    } else {
                        showError(response.data.data || 'Lỗi khi tải dữ liệu');
                    }
                } catch (error) {
                    console.error('Error loading profit data:', error);
                    showError('Lỗi kết nối khi tải dữ liệu');
                } finally {
                    showMainLoading(false);
                }
            }

            // Render profit table
            function renderProfitTable(items) {
                const tbody = $('#profitTable tbody');
                tbody.empty();

                if (items.length === 0) {
                    tbody.html(`
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 mb-3"></i>
                                    <p class="mb-0">Không có dữ liệu</p>
                                </div>
                            </td>
                        </tr>
                    `);
                    return;
                }

                items.forEach((item, index) => {
                    const row = `
                        <tr>
                            <td class="text-center">${(currentPage - 1) * perPage + index + 1}</td>
                            <td>${item.agency_name}</td>
                            <td>${item.machine_name}</td>
                            <td class="text-end">${formatMoney(item.total_money)}</td>
                            <td class="text-end">${formatMoney(item.agency_fee)}</td>
                            <td class="text-end">${formatMoney(item.machine_fee)}</td>
                            <td class="text-end fw-bold text-success">${formatMoney(item.profit)}</td>
                            <td class="text-center">${formatDate(item.created_at)}</td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            }

            // Update pagination
            function updatePagination(pagination) {
                const container = $('.pagination-container');
                const { total, current_page, last_page } = pagination;

                // Update record count
                $('#startRecord').text((current_page - 1) * perPage + 1);
                $('#endRecord').text(Math.min(current_page * perPage, total));
                $('#totalRecords').text(total);

                // Generate pagination HTML
                let html = '<ul class="pagination">';

                // Previous button
                html += `
                    <li class="page-item ${current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${current_page - 1}">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                `;

                // Page numbers
                for (let i = 1; i <= last_page; i++) {
                    if (
                        i === 1 || // First page
                        i === last_page || // Last page
                        (i >= current_page - 2 && i <= current_page + 2) // Pages around current
                    ) {
                        html += `
                            <li class="page-item ${i === current_page ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${i}">${i}</a>
                            </li>
                        `;
                    } else if (
                        i === current_page - 3 || // Before current range
                        i === current_page + 3 // After current range
                    ) {
                        html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }

                // Next button
                html += `
                    <li class="page-item ${current_page === last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${current_page + 1}">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                `;

                html += '</ul>';
                container.html(html);
            }

            // Update statistics
            function updateStatistics(stats) {
                $('#totalProfit').text(formatMoney(stats.total_profit));
                $('#totalBusinesses').text(stats.total_businesses);
                $('#totalAgencies').text(stats.total_agencies);
                $('#averageProfit').text(formatMoney(stats.average_profit));
            }

            // Bind events
            function bindEvents() {
                // Filter form submit
                $('#filterForm').on('submit', function(e) {
                    e.preventDefault();
                    currentPage = 1;
                    loadProfitData();
                });

                // Reset filters
                $('#filterForm').on('reset', function() {
                    setTimeout(() => {
                        currentPage = 1;
                        loadProfitData();
                    }, 0);
                });

                // Pagination click
                $(document).on('click', '.pagination .page-link', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');
                    if (page && page !== currentPage) {
                        currentPage = page;
                        loadProfitData();
                    }
                });
            }

            // Utility functions
            function formatMoney(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount);
            }

            function formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('vi-VN', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            function showMainLoading(show) {
                const overlay = $('#mainLoadingOverlay');
                if (show) {
                    overlay.removeClass('d-none');
                } else {
                    overlay.addClass('d-none');
                }
            }

            function showError(message) {
                Swal.fire({
                    title: 'Lỗi!',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            }
        });
    </script>
@endsection
