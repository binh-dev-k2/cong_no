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
                                <label for="agencyName" class="form-label fw-semibold required">Tên đại lý</label>
                                <input type="text" class="form-control form-control-lg" id="agencyName"
                                    placeholder="Nhập tên đại lý..." required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="agencyFeePercent" class="form-label fw-semibold required">% Phí</label>
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
                                <label for="agencyMachineFeePercent" class="form-label fw-semibold required">% Phí máy</label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-lg" id="agencyMachineFeePercent"
                                        placeholder="0.00" step="0.01" min="0" max="100" required>
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
                                    <span class="fw-bold text-primary" id="selectedMachineCount">Đã chọn: 0 máy</span>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <span class="fw-bold text-primary" id="selectedUserCount">Đã chọn: 0 người dùng</span>
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
