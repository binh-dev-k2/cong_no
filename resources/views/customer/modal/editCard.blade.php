<div class="modal fade" id="modal_edit_card" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="#" id="edit_card_form">
                <div class="modal-header" id="modal_edit_card_header">
                    <h2 class="fw-bold">Sửa thông tin thẻ</h2>
                    <div id="modal_edit_card_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="modal_edit_card_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#modal_edit_card_header"
                        data-kt-scroll-wrappers="#modal_edit_card_scroll" data-kt-scroll-offset="300px">
                        <input type="hidden" name="id">
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="card_number">Số
                                thẻ</label>
                            <input type="number" class="form-control form-control-solid" placeholder=""
                                name="card_number" id="card_number" />
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="account_name">Chủ tài
                                khoản</label>
                            <input type="text" class="form-control form-control-solid" placeholder=""
                                name="account_name" id="account_name" />
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="account_number">Số tài
                                khoản</label>
                            <input type="number" class="form-control form-control-solid" placeholder=""
                                name="account_number" id="account_number" />
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="required fs-6 fw-semibold mb-2" for="select_bank_list">
                                Ngân hàng
                            </label>
                            <select class="form-select form-select-transparent" data-hide-search="false"
                                placeholder="Chọn ngân hàng" id="select_bank_list" name="bank_code">
                                {{-- <option></option> --}}
                                @foreach ($banks as $key => $bank)
                                    <option @if ($key == 0) selected @endif
                                        value="{{ $bank->code }}" data-kt-select2-country="{{ $bank->logo }}">
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
                                    <input class="form-control form-control-solid ps-12" placeholder="Chọn ngày"
                                        name="date_due" />
                                </div>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Ngày trả thẻ</label>
                                <div class="position-relative d-flex align-items-center">
                                    <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                    <input class="form-control form-control-solid ps-12" placeholder="Chọn ngày"
                                        name="date_return" />
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-3 fv-row">
                            <label class="fs-6 fw-semibold mb-2" for="login_info">Thông
                                tin đăng nhập</label>
                            <input type="text" class="form-control form-control-solid"
                                placeholder="Nhập thông tin đăng nhập vào tài khoản ngân hàng" name="login_info"
                                id="login_info" />
                        </div>
<!--                        <div class="d-flex flex-column mb-3 fv-row">-->
<!--                            <label class="required fs-6 fw-semibold mb-2" for="fee_percent">Phần trăm phí</label>-->
<!--                            <input type="number" class="form-control form-control-solid"-->
<!--                                placeholder="Nhập phần trăm phí" name="fee_percent" id="fee_percent" />-->
<!--                        </div>-->
<!--                        <div class="d-flex flex-column mb-3 fv-row">-->
<!--                            <label class="required fs-6 fw-semibold mb-2" for="total_money">Tổng số tiền</label>-->
<!--                            <input type="number" class="form-control form-control-solid"-->
<!--                                placeholder="Nhập tổng số tiền" name="total_money" id="total_money" />-->
<!--                        </div>-->
<!--                        <div class="d-flex flex-column mb-3 fv-row">-->
<!--                            <label class="required fs-6 fw-semibold mb-2" for="select_formality">-->
<!--                                Hình thức-->
<!--                            </label>-->
<!--                            <select class="form-select form-select-solid" data-placeholder="Chọn hình thức"-->
<!--                                name="select_formality" id="select_formality">-->
<!--                                <option value="D">Đáo</option>-->
<!--                                <option value="R">Rút</option>-->
<!--                            </select>-->
<!--                        </div>-->
<!--                        <div class="d-flex flex-column mb-3 fv-row">-->
<!--                            <label class="fs-6 fw-semibold mb-2" for="pay_extra">Tiền trả thêm</label>-->
<!--                            <input type="number" class="form-control form-control-solid"-->
<!--                                placeholder="Nhập số tiền trả thêm" name="pay_extra" id="pay_extra" />-->
<!--                        </div>-->
                        <div class="d-flex flex-column mb-3 fv-row">
                            <label class="fs-6 fw-semibold mb-2" for="note">Ghi chú</label>
                            <textarea class="form-control form-control-solid" rows="2" name="note" id="note"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer flex-center">
                        <button type="reset" id="modal_edit_card_cancel"
                                class="btn btn-light me-3">Đóng</button>
                        <button type="submit" id="modal_edit_card_submit" class="btn btn-primary">
                            <span class="indicator-label">Xác nhận</span>
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
