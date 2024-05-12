<div class="modal fade" id="modal_edit_customer" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="#" id="edit_customer_form">
                <div class="modal-header" id="modal_edit_customer_header">
                    <h2 class="fw-bold">Sửa thông tin khách hàng</h2>
                    <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="modal_edit_customer_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#modal_edit_customer_header"
                        data-kt-scroll-wrappers="#modal_edit_customer_scroll" data-kt-scroll-offset="300px">
                        <input type="hidden" name="id">

                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Họ và tên</label>
                            <input type="text" class="form-control form-control-solid" placeholder=""
                                name="name" />
                        </div>

                        <div class="fv-row mb-7">
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
                            <input type="tel" class="form-control form-control-solid" placeholder=""
                                name="phone" />
                        </div>

                        <div class="fv-row mb-7">
                            <label for="select_add_card" class="required fs-6 fw-semibold mb-2">
                                Nhập số tài khoản hoặc số thẻ
                            </label>

                            <select class="form-select form-select-solid" id="select_edit_card" multiple>
                                <option value="" disabled></option>
                            </select>
                        </div>

                        @include('customer.modal.add_card')
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">Đóng</button>
                    <button type="submit" id="modal_edit_customer_submit" class="btn btn-primary">
                        <span class="indicator-label">Xác nhận</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
