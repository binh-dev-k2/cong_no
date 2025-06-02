<!-- Agency List Component -->
<div class="card shadow-sm" id="agencyListComponent">
    <div class="card-header bg-light-primary">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center w-100 gap-3">
            <div class="flex-grow-1">
                <h3 class="card-title text-primary fw-bold fs-3 mb-0">
                    Danh sách Đại lý
                </h3>
                <p class="text-gray-600 mb-0 mt-2">Quản lý thông tin đại lý và nghiệp vụ đang xử lý</p>
            </div>
            <div class="flex-shrink-0">
                @can('agency-create')
                    <button type="button" class="btn btn-primary" id="addAgencyBtn">
                        <i class="bi bi-plus-circle me-2"></i>
                        Thêm đại lý mới
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="card-body position-relative">
        <!-- Loading Overlay -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center rounded d-none"
            id="mainLoadingOverlay" style="z-index: 1000;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row mb-6">
            <div class="col-md-6">
                <div class="position-relative">
                    <i
                        class="bi bi-search fs-4 position-absolute top-50 start-0 translate-middle-y ms-4 text-gray-500"></i>
                    <input type="text" id="agencySearch" class="form-control form-control-lg ps-12"
                        placeholder="Tìm kiếm đại lý..." />
                </div>
            </div>
        </div>

        <!-- Agencies Accordion Container -->
        <div id="agenciesContainer">
            <!-- Agencies will be loaded here -->
        </div>

        <!-- Empty State -->
        <div class="text-center p-5 text-muted d-none" id="emptyState">
            <i class="bi bi-building fs-1 text-primary opacity-50 mb-3"></i>
            <h4>Chưa có đại lý nào</h4>
            <p>Bắt đầu bằng cách thêm đại lý đầu tiên của bạn</p>
            <button type="button" class="btn btn-primary" id="addFirstAgencyBtn">
                <i class="bi bi-plus-circle me-2"></i>
                Thêm đại lý đầu tiên
            </button>
        </div>
    </div>
</div>
