<!-- Completed Businesses Component -->
<div class="card shadow-sm d-none" id="completedBusinessComponent">
    <div class="card-header bg-light-success">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center w-100 gap-3">
            <div class="flex-grow-1">
                <h3 class="card-title text-success fw-bold fs-3 mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    Nghiệp vụ đã hoàn thành
                </h3>
                <p class="text-gray-600 mb-0 mt-2">Danh sách tất cả nghiệp vụ đã hoàn thành từ các đại lý</p>
            </div>
            <div class="flex-shrink-0">
                <button type="button" class="btn btn-secondary" onclick="showPendingBusinesses()">
                    <i class="bi bi-arrow-left me-2"></i>
                    Về danh sách đại lý
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter Section -->
        <div class="row mb-6">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Lọc theo đại lý:</label>
                <select class="form-select" id="agencyFilterSelect">
                    <option value="">Tất cả đại lý</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Từ ngày:</label>
                <input type="date" class="form-control" id="dateFromFilter">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Đến ngày:</label>
                <input type="date" class="form-control" id="dateToFilter">
            </div>
        </div>

        <!-- DataTable -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="completedBusinessesTable">
                <thead class="table-success">
                    <tr>
                        <th class="text-center">STT</th>
                        <th class="text-center">Đại lý</th>
                        <th class="text-center">Máy</th>
                        <th class="text-center">Tổng tiền</th>
                        <th class="text-center">Mã chuẩn</th>
                        <th class="text-center min-w-125px">Ảnh mặt trước</th>
                        <th class="text-center min-w-125px">Ảnh tổng quan</th>
                        <th class="text-center">Số tiền trả đại lý</th>
                        <th class="text-center">Ngày hoàn thành</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via DataTable -->
                </tbody>
            </table>
        </div>
    </div>
</div>
