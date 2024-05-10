<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="apps/customers/list.html">
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <h2 class="fw-bold">Thêm khách hàng mới</h2>
                    <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                        data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">

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
                            <label class="required fs-6 fw-semibold mb-2">Nhập số tài khoản hoặc số thẻ</label>
                            

                            <select class="form-select form-select-solid" data-control="select2" name="card_number_find" data-placeholder="Chọn thẻ">
                                <option></option>
                            </select>
                        </div>

                        <div class="fw-bold fs-3 rotate collapsible mb-7" data-bs-toggle="collapse"
                            href="#kt_modal_add_customer_billing_info" role="button" aria-expanded="false"
                            aria-controls="kt_customer_view_details">
                            Thông tin ngân hàng
                            <span class="ms-2 rotate-180">
                                <i class="ki-duotone ki-down fs-3"></i>
                            </span>
                        </div>

                        <div id="kt_modal_add_customer_billing_info" class="collapse show">
                            <div id="list_card_added"></div>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row">
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
                            <div id="collapse_add_new_card" class="collapse">
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
                                    <label class="required fs-6 fw-semibold mb-2" for="select_bank_list">Ngân
                                        hàng</label>
                                    <select class="form-select form-select-transparent" placeholder="Chọn ngân hàng"
                                        id="select_bank_list" name="bank_code">
                                        <option></option>
                                        <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($key == 0): ?> selected <?php endif; ?>
                                                value="<?php echo e($bank->code); ?>"
                                                data-kt-select2-country="<?php echo e($bank->logo); ?>">
                                                <?php echo e($bank->shortName); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <input class="form-control form-control-solid ps-12"
                                                placeholder="Chọn ngày" name="date_due" />
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
                                            <input class="form-control form-control-solid ps-12"
                                                placeholder="Chọn ngày" name="date_return" />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column mb-3 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2" for="login_info">Thông
                                        tin đăng nhập</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập thông tin đăng nhập vào tài khoản ngân hàng"
                                        name="login_info" id="login_info" />
                                </div>
                                <div class="d-flex flex-column mb-3 fv-row">
                                    <label class="fs-6 fw-semibold mb-2" for="note">Ghi
                                        chú</label>
                                    <textarea class="form-control form-control-solid" rows="2" name="note" id="note"></textarea>
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
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" id="kt_modal_add_customer_cancel"
                        class="btn btn-light me-3">Discard</button>
                    <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                        <span class="indicator-label">Submit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH D:\CodeTools\laragon\www\WORK\quan_ly_cong_no\resources\views/customer/modal/add.blade.php ENDPATH**/ ?>