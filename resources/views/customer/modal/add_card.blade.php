<div id="kt_modal_add_customer_billing_info" class="collapse show">
    <div id="list_card_added"></div>
</div>

<div class="d-flex flex-column mb-7 fv-row">
    <div class="fw-bold fs-4 rotate collapsible my-3" data-bs-toggle="collapse" href="#collapse_add_new_card" role="button"
        aria-expanded="false" aria-controls="kt_customer_view_details">Thêm thẻ ngân hàng mới
        <span class="ms-2 rotate-180 d-flex justify-content-center ">
            <i class="ki-duotone ki-plus-square fs-3">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
        </span>
    </div>
    <div id="collapse_add_new_card" class="collapse">
        <div class="d-flex flex-column mb-7 fv-row">
            <label class="required fs-6 fw-semibold mb-2" for="card_number">Số
                thẻ</label>
            <input type="number" class="form-control form-control-solid" placeholder="" name="card_number"
                id="card_number" />
        </div>
        <div class="d-flex flex-column mb-7 fv-row">
            <label class="required fs-6 fw-semibold mb-2" for="account_name">Chủ tài
                khoản</label>
            <input type="text" class="form-control form-control-solid" placeholder="" name="account_name"
                id="account_name" />
        </div>
        <div class="d-flex flex-column mb-7 fv-row">
            <label class="required fs-6 fw-semibold mb-2" for="account_number">Số tài
                khoản</label>
            <input type="number" class="form-control form-control-solid" placeholder="" name="account_number"
                id="account_number" />
        </div>
        <div class="d-flex flex-column mb-7 fv-row">
            <label class="required fs-6 fw-semibold mb-2" for="select_bank_list">
                Ngân hàng
            </label>
            <select class="form-select form-select-transparent" data-hide-search="false" placeholder="Chọn ngân hàng"
                id="select_bank_list" name="bank_code">
                {{-- <option></option> --}}
                @foreach ($banks as $key => $bank)
                    <option @if ($key == 0) selected @endif value="{{ $bank->code }}"
                        data-kt-select2-country="{{ $bank->logo }}">
                        {{ $bank->shortName }}</option>
                @endforeach
            </select>
        </div>
        <div class="row g-9 mb-7">
            <div class="col-md-6 fv-row">
                <label class="required fs-6 fw-semibold mb-2">Ngày đến hạn</label>
                <div class="position-relative d-flex align-items-center">
                    <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                        <span class="path6"></span>
                    </i>
                    <input class="form-control form-control-solid ps-12" placeholder="Chọn ngày" name="date_due" />
                </div>
            </div>
            <div class="col-md-6 fv-row">
                <label class="required fs-6 fw-semibold mb-2">Ngày trả thẻ</label>
                <div class="position-relative d-flex align-items-center">
                    <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                        <span class="path6"></span>
                    </i>
                    <input class="form-control form-control-solid ps-12" placeholder="Chọn ngày" name="date_return" />
                </div>
            </div>
        </div>
        <div class="d-flex flex-column mb-3 fv-row">
            <label class="required fs-6 fw-semibold mb-2" for="login_info">Thông
                tin đăng nhập</label>
            <input type="text" class="form-control form-control-solid"
                placeholder="Nhập thông tin đăng nhập vào tài khoản ngân hàng" name="login_info" id="login_info" />
        </div>
        <div class="d-flex flex-column mb-3 fv-row">
            <label class="required fs-6 fw-semibold mb-2" for="fee_percent">Phần trăm phí</label>
            <input type="text" class="form-control form-control-solid"
                   placeholder="Nhập phần trăm phí" name="fee_percent" id="fee_percent" />
        </div>
        <div class="d-flex flex-column mb-3 fv-row">
            <label class="required fs-6 fw-semibold mb-2" for="total_money">Tổng số tiền</label>
            <input type="text" class="form-control form-control-solid"
                   placeholder="Nhập tổng số tiền" name="total_money" id="total_money" />
        </div>
        <div class="d-flex flex-column mb-3 fv-row">
            <label class="required fs-6 fw-semibold mb-2" for="select_formality">
                Hình thức
            </label>
            <select class="form-select form-select-solid"
                    data-placeholder="Chọn hình thức" name="select_formality" id="select_formality">
                <option value="">Chọn hình thức</option>
                <option value="D">D</option>
                <option value="R">R</option>
            </select>
        </div>
        <div class="d-flex flex-column mb-3 fv-row">
            <label class="required fs-6 fw-semibold mb-2" for="pay_extra">Tiền trả thêm</label>
            <input type="text" class="form-control form-control-solid"
                   placeholder="Nhập số tiền trả thêm" name="pay_extra" id="pay_extra" />
        </div>
        <div class="d-flex flex-column mb-3 fv-row">
            <label class="fs-6 fw-semibold mb-2" for="note">Ghi chú</label>
            <textarea class="form-control form-control-solid" rows="2" name="note" id="note"></textarea>
        </div>
        <div class="d-flex justify-content-center mb-3 fv-row ">
            <button type="submit" href="#" id="submit_add_new_card" class="btn btn-primary me-10">
                <span class="indicator-label">
                    <i class="ki-duotone ki-add-item">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Thêm thẻ
                </span>
            </button>
        </div>
    </div>
</div>
