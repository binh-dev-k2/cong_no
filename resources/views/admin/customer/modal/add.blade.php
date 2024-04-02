<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="apps/customers/list.html">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">Thêm khách hàng mới</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                        data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold mb-2">Họ và tên</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-solid" placeholder=""
                                name="name" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-semibold mb-2">
                                <span class="required">Số điện thoại</span>
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="Số điện thoại không được trùng với các khách hàng trước đó">
                                    <i class="ki-duotone ki-information fs-7">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="tel" class="form-control form-control-solid" placeholder=""
                                name="phone" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <!--end::Input group-->
                        <!--begin::Billing toggle-->
                        <div class="fw-bold fs-3 rotate collapsible mb-7" data-bs-toggle="collapse"
                            href="#kt_modal_add_customer_billing_info" role="button" aria-expanded="false"
                            aria-controls="kt_customer_view_details">Thông tin ngân hàng
                            <span class="ms-2 rotate-180">
                                <i class="ki-duotone ki-down fs-3"></i>
                            </span>
                        </div>
                        <!--end::Billing toggle-->
                        <!--begin::Billing form-->
                        <div id="kt_modal_add_customer_billing_info" class="collapse show">
                            <div id="list_card_added">

                                <!--end::Text-->
                            </div>
                        </div>
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-7 fv-row">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-semibold mb-2">Nhập số tài khoản hoặc số thẻ</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <div class="d-flex">
                                <input type="number" class="form-control form-control-solid" placeholder=""
                                    name="card_number_find" />
                                <button type="button" class="btn btn-lg btn-primary"
                                    id="btn_modal_card_add">Thêm</button>
                            </div>
                            <!--end::Input-->
                            <div class="fw-bold fs-4 rotate collapsible my-3" data-bs-toggle="collapse"
                                href="#collapse_add_new_card" role="button" aria-expanded="false"
                                aria-controls="kt_customer_view_details">Thêm thẻ ngân hàng mới
                                <span class="ms-2 rotate-180 d-flex justify-content-center ">
                                    <i class="ki-duotone ki-plus-square fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                            <!--end::Billing toggle-->
                            <!--begin::Billing form-->
                            <div id="collapse_add_new_card" class="collapse">
                                <!--begin::Input group-->

                                <div class="d-flex flex-column mb-7 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2" for="card_number">Số
                                        thẻ</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid" placeholder=""
                                        name="card_number" id="card_number" />
                                    <!--end::Input-->
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2" for="card_name">Chủ tài
                                        khoản</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" placeholder=""
                                        name="card_name" id="card_name" />
                                    <!--end::Input-->
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2" for="account_number">Số tài
                                        khoản</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" class="form-control form-control-solid" placeholder=""
                                        name="account_number" id="account_number" />
                                    <!--end::Input-->
                                </div>
                                <div class="d-flex flex-column mb-7 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2" for="select_bank_list">Ngân
                                        hàng</label>
                                    <select class="form-select form-select-transparent" placeholder="Chọn ngân hàng"
                                        id="select_bank_list" name="bank_id">
                                        <option></option>
                                        @foreach ($list_bank as $key => $bank)
                                            <option @if ($key == 0) selected @endif
                                                value="{{ $bank->code }}"
                                                data-kt-select2-country="{{ $bank->logo }}">
                                                {{ $bank->shortName }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="row g-9 mb-7">
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Ngày đến hạn</label>
                                        <!--begin::Input-->
                                        <div class="position-relative d-flex align-items-center">
                                            <!--begin::Icon-->
                                            <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                                <span class="path6"></span>
                                            </i>
                                            <!--end::Icon-->
                                            <!--begin::Datepicker-->
                                            <input class="form-control form-control-solid ps-12"
                                                placeholder="Chọn ngày" name="date_due" />
                                            <!--end::Datepicker-->
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Ngày trả thẻ</label>
                                        <!--begin::Input-->
                                        <div class="position-relative d-flex align-items-center">
                                            <!--begin::Icon-->
                                            <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                                <span class="path6"></span>
                                            </i>
                                            <!--end::Icon-->
                                            <!--begin::Datepicker-->
                                            <input class="form-control form-control-solid ps-12"
                                                placeholder="Chọn ngày" name="date_return" />
                                            <!--end::Datepicker-->
                                        </div>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Col-->

                                </div>
                                <div class="d-flex flex-column mb-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold mb-2" for="login_info">Thông
                                        tin đăng nhập</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập thông tin đăng nhập vào tài khoản ngân hàng"
                                        name="login_info" id="login_info" />
                                    <!--end::Input-->
                                </div>
                                <div class="d-flex flex-column mb-3 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold mb-2" for="note">Ghi
                                        chú</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea class="form-control form-control-solid" rows="2" name="note" id="note"></textarea>
                                    <!--end::Input-->
                                </div>
                                <div class="d-flex justify-content-center mb-3 fv-row ">

                                    <button type="submit" href="#" id="submit_add_new_card"
                                        class="btn btn-primary me-10">
                                        <span class="indicator-label">
                                            <i class="ki-duotone ki-add-item                        ">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            Thêm thẻ
                                        </span>
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>

                                </div>

                            </div>


                            <!--end::Input group-->

                        </div>
                        <!--end::Billing form-->
                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_add_customer_cancel"
                        class="btn btn-light me-3">Discard</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
