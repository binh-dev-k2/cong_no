<!-- Form Fields Component -->
<div class="form-check form-switch mt-1 mb-3 fv-row">
    <input class="form-check-input" type="checkbox" role="switch" id="is_stranger" name="is_stranger" />
    <label class="form-check-label" for="is_stranger">Khách vãng lai</label>
</div>

<div class="mb-3 fv-row position-relative search-container">
    <div class="d-flex flex-column position-relative">
        <label class="required fs-6 fw-semibold mb-2" for="card_number">
            Số thẻ
            <span class="ms-1" data-bs-toggle="tooltip"
                title="Thẻ khi tìm kiếm sẽ được hiển thị theo dạng: Số thẻ - Tên chủ thẻ - Tên khách hàng - SĐT.">
                <i class="ki-duotone ki-information fs-7">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            </span>
        </label>
        <input type="number" class="form-control form-control-solid" minlength="16" maxlength="16" min="0"
            placeholder="Ex: ************1234" name="card_number" required {{ $readonly ? 'readonly' : '' }} />
        <div class="position-absolute top-50 end-0 me-3">
            <i class="ki-duotone ki-credit-cart fs-2hx">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>
    <ul class="search-card-results search-results list-unstyled shadow-sm rounded mw-650px"></ul>
</div>

<div class="mb-3 fv-row position-relative search-container">
    <div class="d-flex flex-column position-relative">
        <label class="fs-6 fw-semibold mb-2" for="account_name">Chủ tài khoản</label>
        <input type="text" class="form-control form-control-solid" placeholder="Ex: Tran Van A"
            name="account_name" />
    </div>
    <ul class="search-account-results search-results list-unstyled shadow-sm rounded mw-650px"></ul>
</div>

<div class="d-flex flex-column mb-3 fv-row">
    <label class="required fs-6 fw-semibold mb-2" for="name">Tên khách hàng</label>
    <input type="text" class="form-control form-control-solid" placeholder="Ex: Tran Van A" name="name"
        required />
</div>

<div class="d-flex flex-column mb-3 fv-row">
    <label class="required fs-6 fw-semibold mb-2" for="phone">SĐT</label>
    <input type="number" class="form-control form-control-solid" min="0" placeholder="Ex: 0123456789"
        name="phone" required />
</div>

<div class="d-flex flex-column mb-3 fv-row">
    <label class="required fs-6 fw-semibold mb-2" for="fee_percent">% phí</label>
    <input type="number" class="form-control form-control-solid" placeholder="Ex: 21" step="any" name="fee_percent"
        required />
</div>

<div class="d-flex flex-column mb-3 fv-row">
    <label class="required fs-6 fw-semibold mb-2">Hình thức</label>
    <div class="d-flex">
        <div class="form-check me-5">
            <input class="form-check-input" type="radio" value="Đ" name="formality"
                {{ !$readonly ? 'checked' : '' }}>
            <label class="form-check-label">Đáo</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" value="R" name="formality">
            <label class="form-check-label">Rút</label>
        </div>
    </div>
</div>

@if (!$readonly)
    <div class="d-flex flex-column mb-3 fv-row">
        <label class="required fs-6 fw-semibold mb-2" for="total_money">Số tiền</label>
        <input type="text" class="form-control form-control-solid" placeholder="Ex: 1000000" name="total_money"
            required data-type="money" />
    </div>
@else
    <div class="d-flex flex-column mb-3 fv-row">
        <label class="required fs-6 fw-semibold mb-2" for="total_money">Số tiền</label>
        <input type="number" class="form-control form-control-solid" placeholder="Ex: 1000000" name="total_money"
            required readonly />
    </div>
@endif

<div class="d-flex flex-column mb-3 fv-row">
    <label class="fs-6 fw-semibold mb-2 required" for="machine_id">Mã máy</label>
    <select class="form-select form-select-solid" name="machine_id">
        <option value="" disabled selected>Chọn mã máy</option>
    </select>
    <div class="text-muted fs-7 mt-2" id="machine-info-hint"></div>
</div>

<div class="d-flex flex-column mb-3 fv-row">
    <label class="fs-6 fw-semibold mb-2" for="collaborator_id">Cộng tác viên</label>
    <select class="form-select form-select-solid" name="collaborator_id">
        <option value="">Chọn cộng tác viên</option>
        <!-- Options sẽ được render từ JavaScript -->
    </select>
</div>

@if ($showBusinessSettings)
    <div class="d-flex flex-column mb-3 fv-row">
        <label class="required fs-6 fw-semibold mb-2">Khoảng chia</label>
        <div id="business-settings-container">
            <!-- Business settings sẽ được render từ JavaScript -->
        </div>
    </div>
@endif
