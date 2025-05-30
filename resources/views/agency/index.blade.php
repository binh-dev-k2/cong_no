@extends('layouts.layout')
@section('title')
    Quản lý Đại lý
@endsection

@section('header')
    <style>
        .agency-accordion {
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .agency-accordion:hover {
            transform: translateY(-1px);
        }

        .cursor-pointer {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cursor-pointer:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }

        .agency-accordion .accordion-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }

        .agency-info {
            flex-grow: 1;
            min-width: 0;
        }

        .agency-name {
            font-size: 1.3rem;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #2c3e50;
        }

        .agency-stats {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid;
        }

        .stat-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .stat-badge i {
            font-size: 1rem;
        }

        .stat-badge.fee-badge {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1565c0;
            border-color: #90caf9;
        }

        .stat-badge.business-badge {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            color: #2e7d32;
            border-color: #81c784;
        }

        .stat-badge.machine-badge {
            background: linear-gradient(135deg, #fff3e0 0%, #ffcc02 100%);
            color: #ef6c00;
            border-color: #ffb74d;
        }

        .agency-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .btn-edit-agency {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            border: none;
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
            font-weight: 600;
            font-size: 0.85rem;
            margin-left: 0.5rem;
        }

        .btn-edit-agency:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
            color: white;
            background: linear-gradient(135deg, #ffb300 0%, #ff8f00 100%);
        }

        .money-display {
            font-weight: 600;
            color: #1cc88a;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .agency-stats {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            .agency-name {
                font-size: 1.2rem;
            }
            .agency-header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-4 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-2 flex-column justify-content-center my-0">
                    Quản lý Đại lý
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Quản lý Đại lý</li>
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
                                    <div class="text-muted fw-semibold fs-6 mb-2">Tổng đại lý</div>
                                    <div class="fs-2x fw-bold text-primary" id="totalAgencies">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-building fs-3x text-primary opacity-75"></i>
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
                    <div class="card shadow-sm cursor-pointer" onclick="showCompletedBusinesses()">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Đã hoàn thành</div>
                                    <div class="fs-2x fw-bold text-warning" id="completedBusinesses">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-check-circle fs-3x text-warning opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm cursor-pointer" onclick="showPendingBusinesses()">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-muted fw-semibold fs-6 mb-2">Đang xử lý</div>
                                    <div class="fs-2x fw-bold text-danger" id="pendingBusinesses">0</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bi bi-clock fs-3x text-danger opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Component Includes -->
            @include('agency.components.agency-list')
            @include('agency.components.completed-businesses')

            <!-- Agency Modal -->
            <div class="modal fade" id="agencyModal" tabindex="-1" aria-labelledby="agencyModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="agencyModalLabel">Thêm đại lý mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="agencyForm">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-4">
                                            <label for="agencyName" class="form-label fw-semibold">Tên đại lý</label>
                                            <input type="text" class="form-control form-control-lg" id="agencyName"
                                                   placeholder="Nhập tên đại lý..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-4">
                                            <label for="agencyFeePercent" class="form-label fw-semibold">% Phí</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-lg" id="agencyFeePercent"
                                                       placeholder="0.00" step="0.01" min="0" max="100" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Chọn máy cho đại lý</label>
                                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;" id="machineSelector">
                                                <div class="text-center text-muted">
                                                    <div class="spinner-border spinner-border-sm me-2"></div>
                                                    Đang tải danh sách máy...
                                                </div>
                                            </div>
                                            <div class="form-text text-muted mt-2">
                                                <span class="fw-bold text-primary" id="selectedMachineCount">Đã chọn: 0 máy</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                            <button type="button" class="btn btn-primary" id="saveAgencyBtn">
                                <i class="bi bi-check2 me-2"></i>
                                Lưu đại lý
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agency Business Modal -->
            <div class="modal fade" id="agencyBusinessModal" tabindex="-1" aria-labelledby="agencyBusinessModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="agencyBusinessModalLabel">Thêm nghiệp vụ mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="agencyBusinessForm">
                                <input type="hidden" id="businessAgencyId">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="businessMachineId" class="form-label fw-semibold">Máy</label>
                                            <select class="form-select form-select-lg" id="businessMachineId" required>
                                                <option value="">Chọn máy...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="businessTotalMoney" class="form-label fw-semibold">Tổng số tiền</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg" id="businessTotalMoney"
                                                       placeholder="0" required>
                                                <span class="input-group-text">VNĐ</span>
                                            </div>
                                            <div class="form-text text-muted">Nhập số tiền, hệ thống sẽ tự động format</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="businessStandardCode" class="form-label fw-semibold">Mã chuẩn</label>
                                            <input type="text" class="form-control form-control-lg" id="businessStandardCode"
                                                   placeholder="Nhập mã chuẩn..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Empty column for balanced layout -->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="businessImageFront" class="form-label fw-semibold">Ảnh mặt trước</label>
                                            <input type="file" class="form-control form-control-lg" id="businessImageFront"
                                                   accept="image/*">
                                            <div class="form-text text-muted">Chọn file ảnh (jpg, png, gif...)</div>
                                            <div id="currentImageFront" class="mt-2 d-none">
                                                <small class="text-muted">Ảnh hiện tại:</small>
                                                <div class="mt-1">
                                                    <img id="previewImageFront" src="" alt="Current front image"
                                                         style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                                                </div>
                                            </div>
                                            <div id="newImageFrontPreview" class="mt-2 d-none">
                                                <small class="text-muted">Ảnh vừa chọn:</small>
                                                <div class="mt-1">
                                                    <img id="newPreviewImageFront" src="" alt="New front image"
                                                         style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="businessImageSummary" class="form-label fw-semibold">Ảnh tổng kết</label>
                                            <input type="file" class="form-control form-control-lg" id="businessImageSummary"
                                                   accept="image/*">
                                            <div class="form-text text-muted">Chọn file ảnh (jpg, png, gif...)</div>
                                            <div id="currentImageSummary" class="mt-2 d-none">
                                                <small class="text-muted">Ảnh hiện tại:</small>
                                                <div class="mt-1">
                                                    <img id="previewImageSummary" src="" alt="Current summary image"
                                                         style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                                                </div>
                                            </div>
                                            <div id="newImageSummaryPreview" class="mt-2 d-none">
                                                <small class="text-muted">Ảnh vừa chọn:</small>
                                                <div class="mt-1">
                                                    <img id="newPreviewImageSummary" src="" alt="New summary image"
                                                         style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                            <button type="button" class="btn btn-primary" id="saveAgencyBusinessBtn">
                                <i class="bi bi-check2 me-2"></i>
                                Lưu nghiệp vụ
                            </button>
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
            let agencies = [];
            let machines = [];
            let currentAgencyId = null;
            let currentBusinessId = null;
            let isEditing = false;
            let currentView = 'agencies'; // 'agencies' or 'completed'
            let allCompletedBusinesses = [];

            // Initialize
            init();

            async function init() {
                await loadAgencies();
                bindEvents();
            }

            // Load agencies
            async function loadAgencies() {
                showMainLoading(true);

                try {
                    const response = await fetch("/api/agency/list", {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                        },
                    });

                    const data = await response.json();
                    if (data.code === 0) {
                        agencies = data.data;
                        renderAgencies();
                        updateStatistics();
                    } else {
                        showError(data.data || 'Lỗi khi tải danh sách đại lý');
                    }
                } catch (error) {
                    console.error('Error loading agencies:', error);
                    showError('Lỗi kết nối khi tải danh sách đại lý');
                } finally {
                    showMainLoading(false);
                }
            }

            // Load statistics
            async function updateStatistics() {
                try {
                    const response = await fetch("/api/agency/statistics", {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                        },
                    });

                    const data = await response.json();
                    if (data.code === 0) {
                        $('#totalAgencies').text(data.data.total_agencies);
                        $('#totalBusinesses').text(data.data.total_businesses);
                        $('#completedBusinesses').text(data.data.completed_businesses);
                        $('#pendingBusinesses').text(data.data.pending_businesses);
                    }
                } catch (error) {
                    console.error('Error loading statistics:', error);
                    // Fallback to basic count
                    const totalAgencies = agencies.length;
                    const totalBusinesses = agencies.reduce((sum, agency) => sum + agency.agency_businesses_count, 0);

                    $('#totalAgencies').text(totalAgencies);
                    $('#totalBusinesses').text(totalBusinesses);
                    $('#completedBusinesses').text('--');
                    $('#pendingBusinesses').text('--');
                }
            }

            // Render agencies accordion
            function renderAgencies() {
                const container = $('#agenciesContainer');
                const emptyState = $('#emptyState');

                if (agencies.length === 0) {
                    container.empty();
                    emptyState.removeClass('d-none');
                    return;
                }

                emptyState.addClass('d-none');
                container.empty();

                agencies.forEach((agency, index) => {
                    const accordionHtml = `
                        <div class="accordion agency-accordion" id="agencyAccordion${agency.id}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading${agency.id}">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse${agency.id}"
                                            aria-expanded="false" aria-controls="collapse${agency.id}"
                                            data-agency-id="${agency.id}">
                                        <div class="agency-header-content">
                                            <div class="agency-info">
                                                <div class="agency-name">
                                                    <i class="bi bi-building-fill me-2 text-primary"></i>
                                                    ${agency.name}
                                                    <div class="btn btn-sm btn-edit-agency"
                                                            onclick="editAgency(event, ${agency.id})" title="Sửa đại lý">
                                                        <i class="bi bi-pencil-square me-1"></i>
                                                        Sửa
                                                    </div>
                                                </div>
                                                <div class="agency-stats">
                                                    <span class="stat-badge fee-badge">
                                                        <i class="bi bi-percent"></i>
                                                        Phí: <span class="fw-bold">${agency.fee_percent}%</span>
                                                    </span>
                                                    <span class="stat-badge business-badge">
                                                        <i class="bi bi-briefcase-fill"></i>
                                                        Nghiệp vụ: <span class="fw-bold">${agency.agency_businesses_count}</span>
                                                    </span>
                                                    <span class="stat-badge machine-badge">
                                                        <i class="bi bi-cpu-fill"></i>
                                                        Máy: <span class="fw-bold">${agency.agency_machines ? agency.agency_machines.length : 0}</span>
                                                    </span>

                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse${agency.id}" class="accordion-collapse collapse"
                                     aria-labelledby="heading${agency.id}" data-bs-parent="#agencyAccordion${agency.id}">
                                    <div class="accordion-body">
                                        <div class="bg-white m-3 rounded shadow-sm">
                                            <div class="p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-4">
                                                    <h6 class="fw-bold text-gray-800 mb-0">
                                                        <i class="bi bi-list-ul me-2 text-primary"></i>
                                                        Nghiệp vụ của ${agency.name}
                                                    </h6>
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            onclick="addAgencyBusiness(${agency.id})">
                                                        <i class="bi bi-plus-circle me-1"></i>
                                                        Thêm nghiệp vụ
                                                    </button>
                                                </div>
                                                <div id="businessTableContainer${agency.id}">
                                                    <div class="text-center py-4">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(accordionHtml);
                });
            }

            // Load agency businesses when accordion is opened
            $(document).on('shown.bs.collapse', '[id^="collapse"]', function() {
                const agencyId = $(this).attr('id').replace('collapse', '');

                // Use businesses data from agencies list instead of API call
                const agency = agencies.find(a => a.id == agencyId);
                if (agency && agency.agency_businesses) {
                    // Only show pending businesses (is_completed = false) in agency accordion
                    const pendingBusinesses = agency.agency_businesses.filter(b => !b.is_completed);
                    renderBusinessTable(agencyId, pendingBusinesses);
                } else {
                    // Fallback to API call if data not available
                    loadAgencyBusinesses(agencyId);
                }
            });

            // Load businesses for specific agency (FALLBACK - should rarely be used now)
            async function loadAgencyBusinesses(agencyId) {
                const container = $(`#businessTableContainer${agencyId}`);

                try {
                    const response = await fetch(`/api/agency/businesses?agency_id=${agencyId}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                        },
                    });

                    const data = await response.json();
                    if (data.code === 0) {
                        renderBusinessTable(agencyId, data.data);
                    } else {
                        container.html('<div class="alert alert-danger">Lỗi khi tải nghiệp vụ</div>');
                    }
                } catch (error) {
                    console.error('Error loading businesses:', error);
                    container.html('<div class="alert alert-danger">Lỗi kết nối</div>');
                }
            }

            // Render business table
            function renderBusinessTable(agencyId, businesses) {
                const container = $(`#businessTableContainer${agencyId}`);

                if (businesses.length === 0) {
                    container.html(`
                        <div class="text-center p-4 text-muted">
                            <i class="bi bi-briefcase fs-1 text-primary opacity-50 mb-3"></i>
                            <h6>Chưa có nghiệp vụ nào</h6>
                            <p class="mb-0">Bắt đầu bằng cách thêm nghiệp vụ đầu tiên</p>
                        </div>
                    `);
                    return;
                }

                let tableHtml = `
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Máy</th>
                                    <th class="text-center">Tổng tiền</th>
                                    <th class="text-center">Mã chuẩn</th>
                                    <th class="text-center">Số tiền trả đại lý</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                businesses.forEach((business, index) => {
                    // Calculate agency fee: fee_percent * total_money / 100
                    const currentAgency = agencies.find(a => a.id == agencyId);
                    const agencyFeePercent = currentAgency ? currentAgency.fee_percent : 0;
                    const agencyMoney = Math.round(business.total_money * agencyFeePercent / 100);
                    const formattedAgencyMoney = new Intl.NumberFormat('vi-VN').format(agencyMoney);

                    const formattedMoney = new Intl.NumberFormat('vi-VN').format(business.total_money);

                    const machineName = business.machine ? business.machine.name : 'N/A';
                    const machineFee = business.machine && business.machine.fee ?
                        `<small class="text-muted ms-1">(${business.machine.fee}%)</small>` : '';

                    tableHtml += `
                        <tr>
                            <td class="fw-bold">${index + 1}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-cpu me-2 text-primary"></i>
                                    ${machineName}${machineFee}
                                </div>
                            </td>
                            <td class="money-display text-success">${formattedMoney} VNĐ</td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">${business.standard_code}</code>
                            </td>
                            <td class="money-display text-warning">
                                ${formattedAgencyMoney} VNĐ
                                <small class="text-muted d-block">(${agencyFeePercent}%)</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="z-index: 1000;"
                                            onclick="editAgencyBusiness(${business.id})" title="Sửa nghiệp vụ">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    ${!business.is_completed ? `
                                        <button type="button" class="btn btn-sm btn-success"
                                                onclick="completeAgencyBusiness(${business.id})" title="Đánh dấu hoàn thành">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    ` : ''}
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="deleteAgencyBusiness(${business.id})" title="Xóa nghiệp vụ">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                tableHtml += `
                            </tbody>
                        </table>
                    </div>
                `;

                container.html(tableHtml);
            }

            // Show/hide main loading
            function showMainLoading(show) {
                const overlay = $('#mainLoadingOverlay');
                if (show) {
                    overlay.removeClass('d-none');
                } else {
                    overlay.addClass('d-none');
                }
            }

            // Bind events
            function bindEvents() {
                // Add agency button
                $('#addAgencyBtn, #addFirstAgencyBtn').on('click', function() {
                    openAgencyModal();
                });

                // Save agency
                $('#saveAgencyBtn').on('click', saveAgency);

                // Save agency business
                $('#saveAgencyBusinessBtn').on('click', saveAgencyBusiness);

                // Search agencies
                $('#agencySearch').on('keyup', debounce(function() {
                    const searchTerm = $(this).val();
                    filterAgencies(searchTerm);
                }, 300));

                // Form submit prevention
                $('#agencyForm, #agencyBusinessForm').on('submit', function(e) {
                    e.preventDefault();
                });

                // Money formatting
                $('#businessTotalMoney').on('input', function() {
                    formatMoneyInput(this);
                });

                $('#businessTotalMoney').on('blur', function() {
                    validateMoneyInput(this);
                });

                // Image preview handlers
                $('#businessImageFront').on('change', function() {
                    previewImage(this, 'newPreviewImageFront', 'newImageFrontPreview');
                });

                $('#businessImageSummary').on('change', function() {
                    previewImage(this, 'newPreviewImageSummary', 'newImageSummaryPreview');
                });
            }

            // Filter agencies
            function filterAgencies(searchTerm) {
                $('.agency-accordion').each(function() {
                    const agencyName = $(this).find('.accordion-button h5').text();
                    if (agencyName.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            // Open agency modal
            async function openAgencyModal(agencyData = null) {
                isEditing = !!agencyData;
                currentAgencyId = agencyData ? agencyData.id : null;

                $('#agencyModalLabel').text(isEditing ? 'Sửa đại lý' : 'Thêm đại lý mới');
                $('#saveAgencyBtn').html(
                    `<i class="bi bi-check2 me-2"></i>${isEditing ? 'Cập nhật' : 'Lưu đại lý'}`
                );

                if (agencyData) {
                    $('#agencyName').val(agencyData.name);
                    $('#agencyFeePercent').val(agencyData.fee_percent);
                } else {
                    $('#agencyForm')[0].reset();
                }

                // Populate machines dropdown first
                await populateAgencyMachineSelectForModal();

                // Set selected machines if editing (use data from agencies list)
                if (agencyData && agencyData.agency_machines) {
                    const machineIds = agencyData.agency_machines.map(am => am.machine.id);
                    setSelectedMachines(machineIds);
                }

                $('#agencyModal').modal('show');
            }

            // Save agency
            async function saveAgency() {
                const name = $('#agencyName').val().trim();
                const feePercent = $('#agencyFeePercent').val();
                const selectedMachines = getSelectedMachines();

                if (!name || !feePercent) {
                    showError('Vui lòng điền đầy đủ thông tin');
                    return;
                }

                const button = $('#saveAgencyBtn');
                const originalText = button.html();
                button.html('<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...').prop('disabled', true);

                try {
                    let url, requestData;

                    if (isEditing) {
                        url = '/api/agency/update';
                        requestData = {
                            id: currentAgencyId,
                            name: name,
                            fee_percent: parseFloat(feePercent),
                            machines: selectedMachines
                        };
                    } else {
                        url = '/api/agency/store';
                        requestData = {
                            name: name,
                            fee_percent: parseFloat(feePercent),
                            machines: selectedMachines
                        };
                    }

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(requestData)
                    });

                    const data = await response.json();

                    if (data.code === 0) {
                        $('#agencyModal').modal('hide');
                        showSuccess(data.data);
                        await loadAgencies();
                    } else {
                        if (Array.isArray(data.data)) {
                            showError(data.data.join(', '));
                        } else {
                            showError(data.data);
                        }
                    }
                } catch (error) {
                    console.error('Error saving agency:', error);
                    showError('Lỗi khi lưu đại lý');
                } finally {
                    button.html(originalText).prop('disabled', false);
                }
            }

            // Edit agency
            window.editAgency = function(event, agencyId) {
                event.stopPropagation();
                const agency = agencies.find(a => a.id === agencyId);
                if (agency) {
                    openAgencyModal(agency);
                }
            };

            // Add agency business
            window.addAgencyBusiness = function(agencyId) {
                openAgencyBusinessModal(agencyId);
            };

            // Open agency business modal
            function openAgencyBusinessModal(agencyId, businessData = null) {
                isEditing = !!businessData;
                currentAgencyId = agencyId;
                currentBusinessId = businessData ? businessData.id : null;

                $('#agencyBusinessModalLabel').text(
                    isEditing ? 'Sửa nghiệp vụ' : 'Thêm nghiệp vụ mới'
                );
                $('#saveAgencyBusinessBtn').html(
                    `<i class="bi bi-check2 me-2"></i>${isEditing ? 'Cập nhật' : 'Lưu nghiệp vụ'}`
                );

                $('#businessAgencyId').val(agencyId);

                if (businessData) {
                    $('#businessMachineId').val(businessData.machine_id);
                    // Format money value for display
                    const formattedMoney = businessData.total_money.toLocaleString('vi-VN');
                    $('#businessTotalMoney').val(formattedMoney);
                    $('#businessStandardCode').val(businessData.standard_code);

                    // Reset file inputs (can't pre-fill file inputs for security reasons)
                    $('#businessImageFront').val('');
                    $('#businessImageSummary').val('');

                    // Hide new image previews
                    $('#newImageFrontPreview').addClass('d-none');
                    $('#newImageSummaryPreview').addClass('d-none');

                    // Show current images if they exist
                    if (businessData.image_front) {
                        $('#currentImageFront').removeClass('d-none');
                        $('#previewImageFront').attr('src', `/storage/${businessData.image_front}`);
                    } else {
                        $('#currentImageFront').addClass('d-none');
                    }

                    if (businessData.image_summary) {
                        $('#currentImageSummary').removeClass('d-none');
                        $('#previewImageSummary').attr('src', `/storage/${businessData.image_summary}`);
                    } else {
                        $('#currentImageSummary').addClass('d-none');
                    }
                } else {
                    $('#agencyBusinessForm')[0].reset();
                    $('#businessAgencyId').val(agencyId);

                    // Hide all image previews for new business
                    $('#currentImageFront').addClass('d-none');
                    $('#currentImageSummary').addClass('d-none');
                    $('#newImageFrontPreview').addClass('d-none');
                    $('#newImageSummaryPreview').addClass('d-none');
                }

                // Load machines for this specific agency, then set selected machine
                populateAgencyMachineSelect(agencyId).then(() => {
                    $('#businessMachineId').val(businessData.machine_id);
                });

                $('#agencyBusinessModal').modal('show');
            }

            // Save agency business
            async function saveAgencyBusiness() {
                const totalMoneyFormatted = $('#businessTotalMoney').val().trim();
                const totalMoney = getNumericValue(totalMoneyFormatted);

                if (!$('#businessMachineId').val() || !totalMoney || !$('#businessStandardCode').val().trim()) {
                    showError('Vui lòng điền đầy đủ thông tin bắt buộc');
                    return;
                }

                const button = $('#saveAgencyBusinessBtn');
                const originalText = button.html();
                button.html('<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...').prop('disabled', true);

                try {
                    const formData = new FormData();
                    formData.append('agency_id', $('#businessAgencyId').val());
                    formData.append('machine_id', $('#businessMachineId').val());
                    formData.append('total_money', totalMoney);
                    formData.append('standard_code', $('#businessStandardCode').val().trim());

                    // Handle file uploads
                    const imageFront = $('#businessImageFront')[0].files[0];
                    const imageSummary = $('#businessImageSummary')[0].files[0];

                    if (imageFront) {
                        formData.append('image_front', imageFront);
                    }
                    if (imageSummary) {
                        formData.append('image_summary', imageSummary);
                    }

                    let url;
                    if (isEditing) {
                        url = '/api/agency-business/update';
                        formData.append('business_id', currentBusinessId);
                    } else {
                        url = '/api/agency-business/store';
                    }

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.code === 0) {
                        $('#agencyBusinessModal').modal('hide');
                        showSuccess(data.data);
                        // Data will be refreshed when loadAgencies() is called
                        await loadAgencies();
                    } else {
                        if (Array.isArray(data.data)) {
                            showError(data.data.join(', '));
                        } else {
                            showError(data.data);
                        }
                    }
                } catch (error) {
                    console.error('Error saving business:', error);
                    showError('Lỗi khi lưu nghiệp vụ');
                } finally {
                    button.html(originalText).prop('disabled', false);
                }
            }

            // Edit agency business
            window.editAgencyBusiness = async function(businessId) {
                let button = null;
                let originalHTML = '';

                try {
                    // Show loading state
                    if (typeof event !== 'undefined' && event.target) {
                        button = event.target.closest('button');
                        originalHTML = button.innerHTML;
                        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                        button.disabled = true;
                    }

                    // Fetch business details
                    const response = await fetch(`/api/agency/business-details?business_id=${businessId}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (data.code === 0) {
                        const business = data.data;
                        // Open modal with business data
                        openAgencyBusinessModal(business.agency_id, business);
                    } else {
                        showError(data.data || 'Lỗi khi tải thông tin nghiệp vụ');
                    }
                } catch (error) {
                    console.error('Error editing business:', error);
                    showError('Lỗi khi tải thông tin nghiệp vụ');
                } finally {
                    // Restore button state
                    if (button && originalHTML) {
                        button.innerHTML = originalHTML;
                        button.disabled = false;
                    }
                }
            };

            // Complete agency business
            window.completeAgencyBusiness = async function(businessId) {
                const result = await Swal.fire({
                    title: 'Xác nhận hoàn thành',
                    text: 'Bạn có chắc chắn muốn đánh dấu nghiệp vụ này là đã hoàn thành?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Hoàn thành',
                    cancelButtonText: 'Hủy',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-light'
                    }
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch('/api/agency-business/complete', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ business_id: businessId })
                        });

                        const data = await response.json();

                        if (data.code === 0) {
                            showSuccess(data.data);
                            // Data will be refreshed when loadAgencies() is called
                            await loadAgencies();
                        } else {
                            if (Array.isArray(data.data)) {
                                showError(data.data.join(', '));
                            } else {
                                showError(data.data);
                            }
                        }
                    } catch (error) {
                        console.error('Error completing business:', error);
                        showError('Lỗi khi đánh dấu hoàn thành nghiệp vụ');
                    }
                }
            };

            // Delete agency business
            window.deleteAgencyBusiness = async function(businessId) {
                const result = await Swal.fire({
                    title: 'Xác nhận xóa',
                    text: 'Bạn có chắc chắn muốn xóa nghiệp vụ này?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-light'
                    }
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch('/api/agency-business/delete', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ business_id: businessId })
                        });

                        const data = await response.json();

                        if (data.code === 0) {
                            showSuccess(data.data);
                            // Data will be refreshed when loadAgencies() is called
                            await loadAgencies();
                        } else {
                            if (Array.isArray(data.data)) {
                                showError(data.data.join(', '));
                            } else {
                                showError(data.data);
                            }
                        }
                    } catch (error) {
                        console.error('Error deleting business:', error);
                        showError('Lỗi khi xóa nghiệp vụ');
                    }
                }
            };

            // Utility functions
            function showSuccess(message) {
                Swal.fire({
                    title: 'Thành công!',
                    text: message,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
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

            function showInfo(message) {
                Swal.fire({
                    title: 'Thông báo',
                    text: message,
                    icon: 'info',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            }

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

            // Format money input with commas
            function formatMoneyInput(input) {
                let value = input.value.replace(/[^\d]/g, ''); // Remove non-digits
                if (value) {
                    value = parseInt(value).toLocaleString('vi-VN');
                }
                input.value = value;
            }

            // Validate money input
            function validateMoneyInput(input) {
                let value = input.value.replace(/[^\d]/g, '');
                if (!value || parseInt(value) <= 0) {
                    input.setCustomValidity('Vui lòng nhập số tiền hợp lệ');
                } else {
                    input.setCustomValidity('');
                }
            }

            // Get numeric value from formatted money
            function getNumericValue(formattedValue) {
                return parseInt(formattedValue.replace(/[^\d]/g, '')) || 0;
            }

            // Preview image when file is selected
            function previewImage(input, previewId, containerId) {
                const file = input.files[0];
                const preview = document.getElementById(previewId);
                const container = document.getElementById(containerId);

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        container.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    container.classList.add('d-none');
                    preview.src = '';
                }
            }

            // Populate agency machine select dropdown for specific agency (for business modal)
            function populateAgencyMachineSelect(agencyId) {
                const select = $('#businessMachineId');
                select.empty().append('<option value="">Đang tải máy...</option>');

                return fetch(`/api/agency/machines-for-agency?agency_id=${agencyId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    select.empty().append('<option value="">Chọn máy...</option>');

                    if (data.code === 0) {
                        if (data.data.length === 0) {
                            select.append('<option value="" disabled>Đại lý chưa có máy nào</option>');
                        } else {
                            data.data.forEach(machine => {
                                const feeText = machine.fee ? ` (${machine.fee}%)` : '';
                                select.append(`<option value="${machine.id}">${machine.name}${feeText}</option>`);
                            });
                        }
                    } else {
                        select.append('<option value="" disabled>Lỗi khi tải máy</option>');
                    }
                    return true; // Return success
                })
                .catch(error => {
                    console.error('Error loading agency machines:', error);
                    select.empty().append('<option value="" disabled>Lỗi kết nối</option>');
                    return false; // Return error
                });
            }

            // Populate agency machines select dropdown for agency modal (select multiple)
            async function populateAgencyMachineSelectForModal() {
                const container = $('#machineSelector');

                // Show loading state
                container.html('<div class="text-center text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Đang tải danh sách máy...</div>');

                try {
                    const response = await fetch("/api/agency/machines", {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                        },
                    });

                    const data = await response.json();
                    container.empty();

                    if (data.code === 0) {
                        machines = data.data; // Update global machines variable

                        if (machines.length === 0) {
                            container.html('<div class="text-center text-muted">Chưa có máy nào trong hệ thống</div>');
                        } else {
                            machines.forEach(machine => {
                                const feeDisplay = machine.fee ? ` <span class="text-muted">(${machine.fee}%)</span>` : '';
                                container.append(`
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="${machine.id}" id="machine${machine.id}">
                                        <label class="form-check-label" for="machine${machine.id}">
                                            ${machine.name}${feeDisplay}
                                        </label>
                                    </div>
                                `);
                            });

                            // Bind change events to checkboxes
                            container.find('input[type="checkbox"]').on('change', function() {
                                updateSelectedMachineCount();
                            });
                        }
                    } else {
                        container.html('<div class="text-center text-muted">Lỗi khi tải danh sách máy</div>');
                    }

                    // Update counter after loading
                    updateSelectedMachineCount();

                } catch (error) {
                    console.error('Error loading machines:', error);
                    container.html('<div class="text-center text-muted">Lỗi kết nối khi tải danh sách máy</div>');
                }
            }

            // Update selected machine count
            function updateSelectedMachineCount() {
                const container = $('#machineSelector');
                const selectedCount = container.find('input:checked').length;
                $('#selectedMachineCount').text(`Đã chọn: ${selectedCount} máy`);
            }

            // Get selected machines
            function getSelectedMachines() {
                return $('#machineSelector').find('input:checked').map(function() {
                    return this.value;
                }).get();
            }

            // Set selected machines
            function setSelectedMachines(machineIds) {
                const container = $('#machineSelector');
                // Clear all selections first
                container.find('input[type="checkbox"]').prop('checked', false);

                // Check the specified machines
                if (machineIds && machineIds.length > 0) {
                    machineIds.forEach(id => {
                        container.find(`#machine${id}`).prop('checked', true);
                    });
                }

                updateSelectedMachineCount();
            }

            // ============= COMPONENT SWITCHING =============

            // Show pending businesses (agency list view)
            window.showPendingBusinesses = function() {
                currentView = 'agencies';
                $('#agencyListComponent').removeClass('d-none');
                $('#completedBusinessComponent').addClass('d-none');
            };

            // Show completed businesses view
            window.showCompletedBusinesses = async function() {
                currentView = 'completed';
                $('#agencyListComponent').addClass('d-none');
                $('#completedBusinessComponent').removeClass('d-none');

                // Initialize DataTable if not already done
                if (typeof window.initCompletedBusinessesTable === 'function') {
                    window.initCompletedBusinessesTable();
                }
            };

            // View business details (placeholder for future implementation)
            window.viewBusinessDetails = function(businessId) {
                showInfo('Tính năng xem chi tiết sẽ được phát triển sau');
            };

            // ============= COMPLETED BUSINESSES DATATABLE =============

            let completedBusinessesTable;

            // Initialize DataTable when component is shown
            window.initCompletedBusinessesTable = function() {
                console.log('Initializing completed businesses DataTable...');

                if (completedBusinessesTable) {
                    completedBusinessesTable.destroy();
                }

                completedBusinessesTable = $('#completedBusinessesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/api/agency/completed-businesses-datatable',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        data: function(d) {
                            d.agency_id = $('#agencyFilterSelect').val();
                            d.date_from = $('#dateFromFilter').val();
                            d.date_to = $('#dateToFilter').val();
                        },
                        error: function(xhr, error, code) {
                            console.error('DataTable AJAX Error:', error, code, xhr.responseText);
                        }
                    },
                    columns: [
                        {
                            data: null,
                            name: 'stt',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            },
                            className: 'text-center fw-bold'
                        },
                        {
                            data: 'agency',
                            name: 'agency.name',
                            render: function(data, type, row) {
                                return `
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-building me-2 text-primary"></i>
                                        <div>
                                            <div class="fw-semibold">${data.name}</div>
                                            <small class="text-muted">${data.fee_percent}% fee</small>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        {
                            data: 'machine',
                            name: 'machine.name',
                            render: function(data, type, row) {
                                const machineFee = data && data.fee ? ` <small class="text-muted">(${data.fee}%)</small>` : '';
                                const machineName = data ? data.name : 'N/A';
                                return `
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-cpu me-2 text-primary"></i>
                                        ${machineName}${machineFee}
                                    </div>
                                `;
                            }
                        },
                        {
                            data: 'total_money',
                            name: 'total_money',
                            render: function(data, type, row) {
                                const formatted = new Intl.NumberFormat('vi-VN').format(data);
                                return `<span class="money-display text-success">${formatted} VNĐ</span>`;
                            },
                            className: 'text-end'
                        },
                        {
                            data: 'standard_code',
                            name: 'standard_code',
                            render: function(data, type, row) {
                                return `<code class="bg-light px-2 py-1 rounded">${data}</code>`;
                            },
                            className: 'text-center'
                        },
                        {
                            data: null,
                            name: 'agency_money',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                const agencyMoney = Math.round(row.total_money * row.agency.fee_percent / 100);
                                const formatted = new Intl.NumberFormat('vi-VN').format(agencyMoney);
                                return `
                                    <div class="money-display text-warning">
                                        ${formatted} VNĐ
                                        <small class="text-muted d-block">(${row.agency.fee_percent}%)</small>
                                    </div>
                                `;
                            },
                            className: 'text-end'
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at',
                            render: function(data, type, row) {
                                return new Date(data).toLocaleDateString('vi-VN');
                            },
                            className: 'text-center text-muted'
                        },
                        {
                            data: null,
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="viewBusinessDetails(${row.id})" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                onclick="editAgencyBusiness(${row.id})" title="Sửa nghiệp vụ">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </div>
                                `;
                            },
                            className: 'text-center'
                        }
                    ],
                    order: [[6, 'desc']], // Sort by completion date desc
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    language: {
                        url: '/assets/plugins/custom/datatables/i18n/vi.json'
                    },
                    dom: '<"row"<"col-sm-6 d-flex align-items-center justify-content-start"l><"col-sm-6 d-flex align-items-center justify-content-end"f>>' +
                         '<"table-responsive"t>' +
                         '<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"i><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"p>>',
                    drawCallback: function(settings) {
                        // Custom styling after table draw
                        $('#completedBusinessesTable_wrapper .dataTables_filter input').addClass('form-control');
                        $('#completedBusinessesTable_wrapper .dataTables_length select').addClass('form-select');
                    }
                });

                console.log('DataTable initialized successfully');

                // Load agency filter options
                loadAgencyFilterOptions();

                // Bind filter events
                bindCompletedBusinessEvents();
            };

            // Load agency options for filter
            function loadAgencyFilterOptions() {
                console.log('Loading agency filter options...');
                fetch('/api/agency/list', {
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.code === 0) {
                        const select = $('#agencyFilterSelect');
                        select.empty().append('<option value="">Tất cả đại lý</option>');

                        data.data.forEach(agency => {
                            select.append(`<option value="${agency.id}">${agency.name}</option>`);
                        });
                        console.log('Agency filter options loaded successfully');
                    }
                })
                .catch(error => {
                    console.error('Error loading agencies for filter:', error);
                });
            }

            // Bind events for completed business filters
            function bindCompletedBusinessEvents() {
                console.log('Binding completed business filter events...');

                // Remove existing handlers to prevent duplicates
                $('#agencyFilterSelect, #dateFromFilter, #dateToFilter').off('change.completedBusinesses');

                // Bind new handlers
                $('#agencyFilterSelect, #dateFromFilter, #dateToFilter').on('change.completedBusinesses', function() {
                    console.log('Filter changed, reloading DataTable...');
                    if (completedBusinessesTable) {
                        completedBusinessesTable.ajax.reload();
                    }
                });
            }
        });
    </script>
@endsection
