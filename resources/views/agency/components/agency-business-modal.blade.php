<div class="modal fade" id="agencyBusinessModal" tabindex="-1" aria-labelledby="agencyBusinessModalLabel"
    aria-hidden="true">
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
                                <label for="businessMachineId" class="form-label fw-semibold required">Máy</label>
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
                                    <input type="text" class="form-control form-control-lg" id="businessTotalMoney"
                                        placeholder="0" required>
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
                                <input type="text" class="form-control form-control-lg" id="businessStandardCode"
                                    placeholder="Nhập mã chuẩn chi...">
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
                                <label for="businessImageSummary" class="form-label fw-semibold">Ảnh tổng
                                    kết</label>
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
