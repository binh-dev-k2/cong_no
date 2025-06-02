@extends('layouts.layout')
@section('title')
    Quản lý Đại lý
@endsection

@section('header')
    <style>
        /* Minimal custom styles - using mostly utility classes */
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

        /* Style cho ảnh thumbnail */
        .thumbnail-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .thumbnail-image:hover {
            transform: scale(1.05);
        }
    </style>
    <!-- Thêm Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
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
                @if (auth()->user()->can('agency-create') || auth()->user()->can('agency-update'))
                    <div class="col-md-3">
                        <div class="card shadow-sm hover-lift">
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
                @endif

                <div class="col-md-3">
                    <div class="card shadow-sm hover-lift" style="cursor: pointer;" onclick="showCompletedBusinesses()">
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
                    <div class="card shadow-sm hover-lift" style="cursor: pointer;" onclick="showPendingBusinesses()">
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
                                            <label for="agencyName" class="form-label fw-semibold required">Tên đại
                                                lý</label>
                                            <input type="text" class="form-control form-control-lg" id="agencyName"
                                                placeholder="Nhập tên đại lý..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-4">
                                            <label for="agencyFeePercent" class="form-label fw-semibold required">%
                                                Phí</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-lg"
                                                    id="agencyFeePercent" placeholder="0.00" step="0.01" min="0"
                                                    max="100" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label for="agencyMachineFeePercent" class="form-label fw-semibold required">%
                                                Phí máy</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-lg"
                                                    id="agencyMachineFeePercent" placeholder="0.00" step="0.01"
                                                    min="0" max="100" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold required">Chọn máy cho đại lý</label>
                                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;"
                                                id="machineSelector">
                                                <div class="text-center text-muted">
                                                    <div class="spinner-border spinner-border-sm me-2"></div>
                                                    Đang tải danh sách máy...
                                                </div>
                                            </div>
                                            <div class="form-text text-muted mt-2">
                                                <span class="fw-bold text-primary" id="selectedMachineCount">Đã chọn: 0
                                                    máy</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Thêm phần chọn users -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold required">Chọn người dùng quản lý</label>
                                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;"
                                                id="userSelector">
                                                <div class="text-center text-muted">
                                                    <div class="spinner-border spinner-border-sm me-2"></div>
                                                    Đang tải danh sách người dùng...
                                                </div>
                                            </div>
                                            <div class="form-text text-muted mt-2">
                                                <span class="fw-bold text-primary" id="selectedUserCount">Đã chọn: 0 người
                                                    dùng</span>
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
            <div class="modal fade" id="agencyBusinessModal" tabindex="-1" aria-labelledby="agencyBusinessModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="agencyBusinessModalLabel">Thêm nghiệp vụ mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="agencyBusinessForm">
                                <input type="hidden" id="businessAgencyId">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="businessMachineId"
                                                class="form-label fw-semibold required">Máy</label>
                                            <select class="form-select form-select-lg" id="businessMachineId" required>
                                                <option value="">Chọn máy...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="businessTotalMoney" class="form-label fw-semibold required">Tổng
                                                số tiền</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg"
                                                    id="businessTotalMoney" placeholder="0" required>
                                                <span class="input-group-text">VNĐ</span>
                                            </div>
                                            <div class="form-text text-muted">Nhập số tiền, hệ thống sẽ tự động format
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="businessStandardCode" class="form-label fw-semibold">
                                                Mã chuẩn chi
                                            </label>
                                            <input type="text" class="form-control form-control-lg"
                                                id="businessStandardCode" placeholder="Nhập mã chuẩn chi...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Empty column for balanced layout -->
                                    </div>
                                </div>
                                <div class="row image-upload-fields">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="businessImageFront" class="form-label fw-semibold">Ảnh mặt
                                                trước</label>
                                            <input type="file" class="form-control form-control-lg"
                                                id="businessImageFront" accept="image/*">
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
                                            <label for="businessImageSummary" class="form-label fw-semibold">Ảnh tổng
                                                kết</label>
                                            <input type="file" class="form-control form-control-lg"
                                                id="businessImageSummary" accept="image/*">
                                            <div class="form-text text-muted">Chọn file ảnh (jpg, png, gif...)</div>
                                            <div id="currentImageSummary" class="mt-2 d-none">
                                                <small class="text-muted">Ảnh hiện tại:</small>
                                                <div class="mt-1">
                                                    <img id="previewImageSummary" src=""
                                                        alt="Current summary image"
                                                        style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                                                </div>
                                            </div>
                                            <div id="newImageSummaryPreview" class="mt-2 d-none">
                                                <small class="text-muted">Ảnh vừa chọn:</small>
                                                <div class="mt-1">
                                                    <img id="newPreviewImageSummary" src=""
                                                        alt="New summary image"
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
    <!-- Thêm Fancybox JS -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        $(document).ready(function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let agencies = [];
            let machines = [];
            let users = [];
            let currentAgencyId = null;
            let currentBusinessId = null;
            let isEditing = false;
            let currentView = 'agencies'; // 'agencies' or 'completed'
            let allCompletedBusinesses = [];

            // Setup axios defaults with CSRF token
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
            axios.defaults.headers.common['Accept'] = 'application/json';
            axios.defaults.headers.common['Content-Type'] = 'application/json';

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
                    const response = await axios.get("/api/agency/list");

                    if (response.data.code === 0) {
                        agencies = response.data.data;
                        renderAgencies();
                        updateStatistics();
                    } else {
                        showError(response.data.data || 'Lỗi khi tải danh sách đại lý');
                    }
                } catch (error) {
                    console.error('Error loading agencies:', error);
                    showError('Lỗi kết nối khi tải danh sách đại lý');
                } finally {
                    showMainLoading(false);
                }
            }

            // Hàm load danh sách users
            async function loadUsers() {
                try {
                    const response = await axios.get('/api/user/list');
                    if (response.data.code === 0) {
                        users = response.data.data;
                        renderUserSelector();
                    }
                } catch (error) {
                    console.error('Error loading users:', error);
                }
            }

            // Load statistics
            async function updateStatistics() {
                try {
                    const response = await axios.get("/api/agency/statistics");

                    if (response.data.code === 0) {
                        $('#totalAgencies').text(response.data.data.total_agencies);
                        $('#totalBusinesses').text(response.data.data.total_businesses);
                        $('#completedBusinesses').text(response.data.data.completed_businesses);
                        $('#pendingBusinesses').text(response.data.data.pending_businesses);
                    }
                } catch (error) {
                    console.error('Error loading statistics:', error);
                    // Fallback to basic count
                    const totalAgencies = agencies.length;
                    const totalBusinesses = agencies.reduce((sum, agency) => sum + agency
                        .agency_businesses_count, 0);

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

                const user = @json(auth()->user());
                agencies.forEach((agency, index) => {
                    const canEdit = @json(auth()->user()->can('agency-update'));
                    const canManage = agency.users.some(u => u.id === user.id) || agency.owner_id === user
                        .id;
                    const isOwner = user.id === agency.owner_id;

                    const accordionHtml = `
                        <div class="accordion mb-4 hover-lift" id="agencyAccordion${agency.id}">
                            <div class="accordion-item">
                                <h2 class="accordion-header position-relative gradient-border" id="heading${agency.id}">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse${agency.id}"
                                            aria-expanded="false" aria-controls="collapse${agency.id}"
                                            data-agency-id="${agency.id}">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="flex-grow-1 min-width-0">
                                                <div class="d-flex align-items-center mb-3 fs-5 fw-bold text-dark">
                                                    <i class="bi bi-building-fill me-2 text-primary"></i>
                                                    ${agency.name}
                                                    ${canEdit ? `
                                                        <div class="btn btn-sm btn-warning rounded-pill ms-2 hover-lift"
                                                                onclick="editAgency(event, ${agency.id})" title="Sửa đại lý">
                                                            <i class="bi bi-pencil-square me-1"></i>
                                                            Sửa
                                                        </div>
                                                    ` : ''}
                                                </div>
                                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                                    <span class="badge badge-light-danger d-flex align-items-center gap-2 px-3 py-2 fs-7 fw-bold">
                                                        <i class="bi bi-percent"></i>
                                                        Phí: ${agency.fee_percent}%
                                                    </span>
                                                    <span class="badge badge-light-success d-flex align-items-center gap-2 px-3 py-2 fs-7 fw-bold">
                                                        <i class="bi bi-briefcase-fill"></i>
                                                        Nghiệp vụ: ${agency.agency_businesses_count}
                                                    </span>
                                                    <span class="badge badge-light-warning d-flex align-items-center gap-2 px-3 py-2 fs-7 fw-bold">
                                                        <i class="bi bi-cpu-fill"></i>
                                                        Máy: ${agency.agency_machines ? agency.agency_machines.length : 0}
                                                    </span>
                                                    ${isOwner ? `
                                                        <span class="badge badge-light-info d-flex align-items-center gap-2 px-3 py-2 fs-7 fw-bold">
                                                            <i class="bi bi-person-fill"></i>
                                                            Người dùng: ${agency.agency_users_count}
                                                        </span>
                                                        <span class="badge badge-light-primary d-flex align-items-center gap-2 px-3 py-2 fs-7 fw-bold">
                                                            <i class="bi bi-gear-fill"></i>
                                                            Phí máy: ${agency.machine_fee_percent}%
                                                        </span>
                                                    ` : ''}
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
                                                    ${canManage ? `
                                                                                    <button type="button" class="btn btn-sm btn-primary"
                                                                                            onclick="addAgencyBusiness(${agency.id})">
                                                                                        <i class="bi bi-plus-circle me-1"></i>
                                                                                        Thêm nghiệp vụ
                                                                                    </button>
                                                                                ` : ''}
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


            // Hàm render user selector
            function renderUserSelector() {
                const container = $('#userSelector');
                container.empty();

                const currentUserId = @json(auth()->id());

                users.forEach(user => {
                    // Bỏ qua user hiện tại
                    if (user.id === currentUserId) return;

                    container.append(`
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="${user.id}" id="user${user.id}">
                            <label class="form-check-label" for="user${user.id}">
                                ${user.name} (${user.email})
                            </label>
                        </div>
                    `);
                });

                // Bind change events
                container.find('input[type="checkbox"]').on('change', function() {
                    updateSelectedUserCount();
                });
            }

            // Hàm cập nhật số lượng user đã chọn
            function updateSelectedUserCount() {
                const selectedCount = $('#userSelector').find('input:checked').length;
                $('#selectedUserCount').text(`Đã chọn: ${selectedCount} người dùng`);
            }

            // Hàm lấy danh sách user đã chọn
            function getSelectedUsers() {
                return $('#userSelector').find('input:checked').map(function() {
                    return this.value;
                }).get();
            }

            // Hàm set selected users
            function setSelectedUsers(userIds) {
                const container = $('#userSelector');
                container.find('input[type="checkbox"]').prop('checked', false);

                if (userIds && userIds.length > 0) {
                    userIds.forEach(id => {
                        container.find(`#user${id}`).prop('checked', true);
                    });
                }

                updateSelectedUserCount();
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
                    const response = await axios.get(`/api/agency/businesses?agency_id=${agencyId}`);

                    if (response.data.code === 0) {
                        renderBusinessTable(agencyId, response.data.data);
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
                                    <th class="text-center">Mã chuẩn chi</th>
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
                    const agencyMoney = Math.round(business.total_money - (business.total_money *
                        agencyFeePercent / 100));
                    const formattedAgencyMoney = new Intl.NumberFormat('vi-VN').format(agencyMoney);

                    const formattedMoney = new Intl.NumberFormat('vi-VN').format(business.total_money);

                    const machineName = business.machine ? business.machine.name : 'N/A';

                    tableHtml += `
                        <tr>
                            <td class="text-center fw-bold">${index + 1}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cpu me-2 text-primary"></i>
                                    <span class="fw-bold">${machineName}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-success">${formattedMoney}</span>
                            </td>
                            <td class="text-center">
                                ${business.standard_code ? `<span class="badge badge-light px-3 py-2">${business.standard_code}</span>` : '<span class="text-muted">--</span>'}
                            </td>
                            <td class="text-center">
                                <div class="fw-bold text-warning">
                                    ${formattedAgencyMoney}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary hover-lift"
                                            onclick="editAgencyBusiness(${business.id})" title="Sửa nghiệp vụ">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    ${!business.is_completed ? `
                                                                                    <button type="button" class="btn btn-sm btn-success hover-lift"
                                                                                            onclick="completeAgencyBusiness(${business.id})" title="Đánh dấu hoàn thành">
                                                                                        <i class="bi bi-check-circle"></i>
                                                                                    </button>
                                                                                ` : ''}
                                    <button type="button" class="btn btn-sm btn-outline-danger hover-lift"
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
                    $('#agencyMachineFeePercent').val(agencyData.machine_fee_percent);
                } else {
                    $('#agencyForm')[0].reset();
                }

                // Load users và machines
                await Promise.all([
                    loadUsers(),
                    populateAgencyMachineSelectForModal()
                ]);

                // Set selected machines và users nếu đang edit
                if (agencyData) {
                    if (agencyData.agency_machines) {
                        const machineIds = agencyData.agency_machines.map(am => am.machine.id);
                        setSelectedMachines(machineIds);
                    }
                    if (agencyData.users) {
                        const userIds = agencyData.users.map(u => u.id);
                        setSelectedUsers(userIds);
                    }
                }

                $('#agencyModal').modal('show');
            }

            // Save agency
            async function saveAgency() {
                const name = $('#agencyName').val().trim();
                const feePercent = $('#agencyFeePercent').val();
                const machineFeePercent = $('#agencyMachineFeePercent').val();
                const selectedMachines = getSelectedMachines();
                const selectedUsers = getSelectedUsers();

                if (!name || !feePercent || !machineFeePercent) {
                    showError('Vui lòng điền đầy đủ thông tin');
                    return;
                }

                const button = $('#saveAgencyBtn');
                const originalText = button.html();
                button.html('<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...').prop(
                    'disabled', true);

                try {
                    let url, requestData;

                    if (isEditing) {
                        url = '/api/agency/update';
                        requestData = {
                            id: currentAgencyId,
                            name: name,
                            fee_percent: parseFloat(feePercent),
                            machine_fee_percent: parseFloat(machineFeePercent),
                            machines: selectedMachines,
                            users: selectedUsers
                        };
                    } else {
                        url = '/api/agency/store';
                        requestData = {
                            name: name,
                            fee_percent: parseFloat(feePercent),
                            machine_fee_percent: parseFloat(machineFeePercent),
                            machines: selectedMachines,
                            users: selectedUsers
                        };
                    }

                    const response = await axios.post(url, requestData);

                    if (response.data.code === 0) {
                        $('#agencyModal').modal('hide');
                        showSuccess(response.data.data);
                        await loadAgencies();
                    } else {
                        if (Array.isArray(response.data.data)) {
                            showError(response.data.data.join(', '));
                        } else {
                            showError(response.data.data);
                        }
                    }
                } catch (error) {
                    console.error('Error saving agency:', error);
                    if (error.response && error.response.data && error.response.data.data) {
                        if (Array.isArray(error.response.data.data)) {
                            showError(error.response.data.data.join(', '));
                        } else {
                            showError(error.response.data.data);
                        }
                    } else {
                        showError('Lỗi khi lưu đại lý');
                    }
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

                // Show/hide image upload fields based on editing mode
                const imageFields = $('.image-upload-fields');
                if (isEditing) {
                    imageFields.show();
                } else {
                    imageFields.hide();
                }

                if (businessData) {
                    $('#businessMachineId').val(businessData.machine_id);
                    // Format money value for display
                    const formattedMoney = businessData.total_money.toLocaleString('vi-VN');
                    $('#businessTotalMoney').val(formattedMoney);
                    $('#businessStandardCode').val(businessData.standard_code || '');

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
                    if (businessData) {
                        $('#businessMachineId').val(businessData.machine_id);
                    }
                });

                $('#agencyBusinessModal').modal('show');
            }

            // Save agency business
            async function saveAgencyBusiness() {
                const totalMoneyFormatted = $('#businessTotalMoney').val().trim();
                const totalMoney = getNumericValue(totalMoneyFormatted);
                const standardCode = $('#businessStandardCode').val().trim();

                if (!$('#businessMachineId').val() || !totalMoney) {
                    showError('Vui lòng điền đầy đủ thông tin bắt buộc (Máy và Tổng số tiền)');
                    return;
                }

                const button = $('#saveAgencyBusinessBtn');
                const originalText = button.html();
                button.html('<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...').prop(
                    'disabled', true);

                try {
                    if (isEditing) {
                        // For editing, use FormData to handle file uploads
                        const formData = new FormData();
                        formData.append('business_id', currentBusinessId);
                        formData.append('agency_id', $('#businessAgencyId').val());
                        formData.append('machine_id', $('#businessMachineId').val());
                        formData.append('total_money', totalMoney);
                        formData.append('standard_code', standardCode);

                        // Handle file uploads only in edit mode
                        const imageFront = $('#businessImageFront')[0].files[0];
                        const imageSummary = $('#businessImageSummary')[0].files[0];

                        if (imageFront) {
                            formData.append('image_front', imageFront);
                        }
                        if (imageSummary) {
                            formData.append('image_summary', imageSummary);
                        }

                        const response = await axios.post('/api/agency-business/update', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        });

                        if (response.data.code === 0) {
                            $('#agencyBusinessModal').modal('hide');
                            showSuccess(response.data.data);
                            await loadAgencies();
                        } else {
                            if (Array.isArray(response.data.data)) {
                                showError(response.data.data.join(', '));
                            } else {
                                showError(response.data.data);
                            }
                        }
                    } else {
                        // For new business, use JSON (no file uploads)
                        const requestData = {
                            agency_id: $('#businessAgencyId').val(),
                            machine_id: $('#businessMachineId').val(),
                            total_money: totalMoney,
                            standard_code: standardCode
                        };

                        const response = await axios.post('/api/agency-business/store', requestData);

                        if (response.data.code === 0) {
                            $('#agencyBusinessModal').modal('hide');
                            showSuccess(response.data.data);
                            await loadAgencies();
                        } else {
                            if (Array.isArray(response.data.data)) {
                                showError(response.data.data.join(', '));
                            } else {
                                showError(response.data.data);
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error saving business:', error);
                    if (error.response && error.response.data && error.response.data.data) {
                        if (Array.isArray(error.response.data.data)) {
                            showError(error.response.data.data.join(', '));
                        } else {
                            showError(error.response.data.data);
                        }
                    } else {
                        showError('Lỗi khi lưu nghiệp vụ');
                    }
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
                    const response = await axios.get(
                        `/api/agency/business-details?business_id=${businessId}`);

                    if (response.data.code === 0) {
                        const business = response.data.data;
                        // Open modal with business data
                        openAgencyBusinessModal(business.agency_id, business);
                    } else {
                        showError(response.data.data || 'Lỗi khi tải thông tin nghiệp vụ');
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
                        const response = await axios.post('/api/agency-business/complete', {
                            business_id: businessId
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.data.code === 0) {
                            showSuccess(response.data.data);
                            await loadAgencies();
                        } else {
                            if (Array.isArray(response.data.data)) {
                                showError(response.data.data.join(', '));
                            } else {
                                showError(response.data.data);
                            }
                        }
                    } catch (error) {
                        console.error('Error completing business:', error);
                        if (error.response && error.response.data && error.response.data.message) {
                            showError(error.response.data.message);
                        } else if (error.response && error.response.data && error.response.data.data) {
                            showError(error.response.data.data);
                        } else {
                            showError('Lỗi khi đánh dấu hoàn thành nghiệp vụ');
                        }
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
                        const response = await axios.post('/api/agency-business/delete', {
                            business_id: businessId
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.data.code === 0) {
                            showSuccess(response.data.data);
                            await loadAgencies();
                        } else {
                            if (Array.isArray(response.data.data)) {
                                showError(response.data.data.join(', '));
                            } else {
                                showError(response.data.data);
                            }
                        }
                    } catch (error) {
                        console.error('Error deleting business:', error);
                        if (error.response && error.response.data && error.response.data.message) {
                            showError(error.response.data.message);
                        } else if (error.response && error.response.data && error.response.data.data) {
                            showError(error.response.data.data);
                        } else {
                            showError('Lỗi khi xóa nghiệp vụ');
                        }
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

                return axios.get(`/api/agency/machines-for-agency?agency_id=${agencyId}`)
                    .then(response => {
                        select.empty().append('<option value="">Chọn máy...</option>');

                        if (response.data.code === 0) {
                            if (response.data.data.length === 0) {
                                select.append('<option value="" disabled>Đại lý chưa có máy nào</option>');
                            } else {
                                response.data.data.forEach(machine => {
                                    const feeText = machine.fee ? ` (${machine.fee}%)` : '';
                                    select.append(
                                        `<option value="${machine.id}">${machine.name}${feeText}</option>`
                                    );
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
                container.html(
                    '<div class="text-center text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Đang tải danh sách máy...</div>'
                );

                try {
                    const response = await axios.get("/api/agency/machines");
                    container.empty();

                    if (response.data.code === 0) {
                        machines = response.data.data; // Update global machines variable

                        if (machines.length === 0) {
                            container.html(
                                '<div class="text-center text-muted">Chưa có máy nào trong hệ thống</div>');
                        } else {
                            machines.forEach(machine => {
                                const feeDisplay = machine.fee ?
                                    ` <span class="text-muted">(${machine.fee}%)</span>` : '';
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
                    container.html(
                        '<div class="text-center text-muted">Lỗi kết nối khi tải danh sách máy</div>');
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
                if (completedBusinessesTable) {
                    completedBusinessesTable.destroy();
                }

                completedBusinessesTable = $('#completedBusinessesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    ajax: {
                        url: '/api/agency/completed-businesses-datatable',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                        },
                        data: function(d) {
                            d.agency_id = $('#agencyFilterSelect').val();
                            d.date_from = $('#dateFromFilter').val();
                            d.date_to = $('#dateToFilter').val();
                        },
                        error: function(xhr, error, code) {
                            console.error('DataTable AJAX Error:', error, code, xhr.responseText);
                        },
                    },
                    columns: [{
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
                                    <div class="fw-bold text-primary text-center">${data.name}</div>
                                `;
                            }
                        },
                        {
                            data: 'machine',
                            name: 'machine.name',
                            render: function(data, type, row) {
                                const machineName = data ? data.name : 'N/A';
                                return `
                                    <div class="text-center">
                                        ${machineName}
                                    </div>
                                `;
                            }
                        },
                        {
                            data: 'total_money',
                            name: 'total_money',
                            render: function(data, type, row) {
                                const formatted = new Intl.NumberFormat('vi-VN').format(data);
                                return `<span class="fw-bold text-success">${formatted}</span>`;
                            },
                            className: 'text-center'
                        },
                        {
                            data: 'standard_code',
                            name: 'standard_code',
                            render: function(data, type, row) {
                                return data ?
                                    `<code class="bg-light px-2 py-1 rounded">${data}</code>` :
                                    '';
                            },
                            className: 'text-center'
                        },
                        {
                            data: 'image_front',
                            name: 'image_front',
                            render: function(data, type, row) {
                                if (!data) return 'N/A';
                                const url = window.location.origin + '/storage/' + data;
                                return `<a href="${url}" data-fancybox="gallery" data-caption="Ảnh trước">
                                    <img src="${url}" loading="lazy" alt="Ảnh trước" class="thumbnail-image">
                                </a>`;
                            },
                            className: 'text-center'
                        },
                        {
                            data: 'image_summary',
                            name: 'image_summary',
                            render: function(data, type, row) {
                                if (!data) return 'N/A';
                                const url = window.location.origin + '/storage/' + data;
                                return `<a href="${url}" data-fancybox="gallery" data-caption="Ảnh tổng quan">
                                    <img src="${url}" loading="lazy" alt="Ảnh tổng quan" class="thumbnail-image">
                                </a>`;
                            },
                            className: 'text-center min-w-100'
                        },
                        {
                            data: 'profit',
                            name: 'profit',
                            searchable: false,
                            render: function(data, type, row) {
                                const formatted = new Intl.NumberFormat('vi-VN').format(data);
                                return `
                                    <div class="fw-bold text-warning text-center">
                                        ${formatted}
                                    </div>
                                `;
                            },
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at',
                            orderable: false,
                            render: function(data, type, row) {
                                return new Date(data).toLocaleDateString('vi-VN');
                            },
                            className: 'text-center'
                        }
                    ],
                    order: [
                        [6, 'desc']
                    ], // Sort by completion date desc
                    pageLength: 25,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    dom: '<"row"<"col-sm-6 d-flex align-items-center justify-content-start"l><"col-sm-6 d-flex align-items-center justify-content-end"f>>' +
                        '<"table-responsive"t>' +
                        '<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"i><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"p>>',
                    drawCallback: function(settings) {
                        // Custom styling after table draw
                        $('#completedBusinessesTable_wrapper .dataTables_filter input').addClass(
                            'form-control');
                        $('#completedBusinessesTable_wrapper .dataTables_length select').addClass(
                            'form-select');
                    }
                });

                // Load agency filter options
                loadAgencyFilterOptions();

                // Bind filter events
                bindCompletedBusinessEvents();
            };

            // Load agency options for filter
            function loadAgencyFilterOptions() {
                axios.get('/api/agency/list')
                    .then(response => {
                        if (response.data.code === 0) {
                            const select = $('#agencyFilterSelect');
                            select.empty().append('<option value="">Tất cả đại lý</option>');

                            response.data.data.forEach(agency => {
                                select.append(`<option value="${agency.id}">${agency.name}</option>`);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading agencies for filter:', error);
                    });
            }

            // Bind events for completed business filters
            function bindCompletedBusinessEvents() {
                // Remove existing handlers to prevent duplicates
                $('#agencyFilterSelect, #dateFromFilter, #dateToFilter').off('change.completedBusinesses');

                // Bind new handlers
                $('#agencyFilterSelect, #dateFromFilter, #dateToFilter').on('change.completedBusinesses',
                    function() {
                        if (completedBusinessesTable) {
                            completedBusinessesTable.ajax.reload();
                        }
                    });
            }

            // Khởi tạo Fancybox
            Fancybox.bind("[data-fancybox]", {
                // Tùy chọn Fancybox
                loop: true,
                buttons: [
                    "zoom",
                    "slideShow",
                    "fullScreen",
                    "close"
                ],
                animationEffect: "zoom-in-out",
                transitionEffect: "fade"
            });
        });
    </script>
@endsection
