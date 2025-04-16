<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="{{ route('dashboard') }}">
            <img alt="Logo" src="assets/media/logos/default.svg"
                class="h-25px app-sidebar-logo-default theme-light-show" />
            <img alt="Logo" src="assets/media/logos/default-dark.svg"
                class="h-25px app-sidebar-logo-default theme-dark-show" />
            <img alt="Logo" src="assets/media/logos/default-small.svg" class="h-20px app-sidebar-logo-minimize" />
        </a>
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-outline ki-black-left-line fs-3 rotate-180"></i>
        </div>
    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true">
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                    data-kt-menu="true" data-kt-menu-expand="false">

                    @can('dashboard')
                        <div class="menu-item">
                            <a class="menu-link  @if (Route::currentRouteName() == 'dashboard') active @endif"
                                href="{{ route('dashboard') }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-graph-2 fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Thống kê</span>
                            </a>
                        </div>
                    @endcan

                    @can('customer-view')
                        <div class="menu-item">
                            <a class="menu-link @if (Route::currentRouteName() == 'customer') active @endif"
                                href="{{ route('customer') }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-people fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Khách hàng</span>
                            </a>
                        </div>
                    @endcan

                    @can('business-view')
                        <div class="menu-item">
                            <a class="menu-link @if (Route::currentRouteName() == 'business') active @endif"
                                href="{{ route('business') }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-tablet-text-down fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Nghiệp vụ</span>
                            </a>
                        </div>
                    @endcan

                    @can('debit-view')
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link @if (Route::currentRouteName() == 'debit') active @endif"
                                href="{{ route('debit') }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-credit-cart fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Ghi Nợ</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    @endcan

                    @can('machine-view')
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link @if (Route::currentRouteName() == 'machine') active @endif"
                                href="{{ route('machine') }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-screen fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Máy</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    @endcan

                    @can('collaborator-view')
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link @if (Route::currentRouteName() == 'collaborator') active @endif"
                                href="{{ route('collaborator') }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-user fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Cộng tác viên</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    @endcan

                    @can('user-view')
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link @if (Route::currentRouteName() == 'user') active @endif" href="{{ route('user') }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-user fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Người dùng</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    @endcan

                    @can('role-view')
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link @if (Route::currentRouteName() == 'role') active @endif"
                                href="{{ route('role') }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-shield fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Vai trò</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    @endcan

                    @can('activity-log-view')
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link @if (Route::currentRouteName() == 'activity-log') active @endif"
                            href="{{ route('activity-log') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-shield fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Lịch sử</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                @endcan
                </div>
            </div>
        </div>
    </div>
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <button
            class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100 btn-add-bank"
            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" title="Thêm ngân hàng">
            <span class="spinner-border spinner-border-sm align-middle me-2 d-none" id="update-code-spinner"></span>
            <span class="btn-label">Thêm ngân hàng</span>
            <i class="ki-outline ki-plus btn-icon fs-2 m-0"></i>
        </button>
        <button
            class="btn btn-flex flex-center btn-custom btn-warning overflow-hidden text-nowrap px-0 h-40px w-100 btn-update-code mt-2"
            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" title="Cập nhật phiên bản mới">
            <span class="spinner-border spinner-border-sm align-middle me-2 d-none" id="update-code-spinner"></span>
            <span class="btn-label">Cập nhật</span>
            <i class="ki-outline ki-refresh btn-icon fs-2 m-0"></i>
        </button>
    </div>
</div>

<div class="modal fade" id="modal_add_bank" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" action="#" id="modal_add_bank_form">
                <div class="modal-header" id="modal_add_bank_header">
                    <h2 class="fw-bold">Thêm ngân hàng</h2>
                    <div id="modal_add_bank_close" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                            <label class="required fs-6 fw-semibold mb-2">Tên ngân hàng</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Nhập tên ngân hàng"
                                name="shortName" />
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold mb-2">
                                <span class="required">Mã ngân hàng</span>
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="Mã ngân hàng không được trùng với các ngân hàng trước đó">
                                    <i class="ki-duotone ki-information fs-7">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </label>
                            <input type="tel" class="form-control form-control-solid" placeholder="Nhập mã ngân hàng"
                                name="code" />
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Logo</label>
                            <input type="url" class="form-control form-control-solid" placeholder="Nhập đường dẫn logo ngân hàng"
                                name="logo" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" id="modal_add_bank_cancel" class="btn btn-light me-3">Đóng</button>
                    <button type="submit" id="modal_add_bank_submit" class="btn btn-primary">
                        <span class="indicator-label">Xác nhận</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateCodeBtn = document.querySelector('.btn-update-code');
        updateCodeBtn.addEventListener('click', function(e) {
            updateCodeBtn.disabled = true;
            const loadingIndicator = updateCodeBtn.querySelector('#update-code-spinner');
            updateCodeBtn.querySelector('.btn-label').textContent = 'Đang cập nhật...';
            loadingIndicator.classList.remove('d-none');
            window.location.href = "{{ route('update-code') }}";
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth < 992) {
                if (!$('#kt_app_sidebar').hasClass('drawer') && !$('#kt_app_sidebar').hasClass(
                        'drawer-start')) {
                    $('#kt_app_sidebar').addClass('drawer drawer-start');
                }
            } else {
                $('#kt_app_sidebar').removeClass('drawer drawer-start');
            }
        });

        const addBankBtn = document.querySelector('.btn-add-bank');
        addBankBtn.addEventListener('click', function(e) {
            addBankBtn.disabled = true;
            addBankBtn.querySelector('.btn-label').textContent = 'Đang thêm...';
            $('#modal_add_bank').modal('show');
        });

        const modalAddBank = document.querySelector('#modal_add_bank');
        modalAddBank.addEventListener('hidden.bs.modal', function(e) {
            addBankBtn.disabled = false;
            addBankBtn.querySelector('.btn-label').textContent = 'Thêm ngân hàng';
        });

        const headers = {
            Authorization: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        };

        const modalAddBankForm = document.querySelector('#modal_add_bank_form');
        modalAddBankForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const shortName = document.querySelector('input[name="shortName"]').value;
            const code = document.querySelector('input[name="code"]').value;
            const logo = document.querySelector('input[name="logo"]').value;
            axios.post("{{ route('api.bank.store') }}", { shortName, code, logo }, { headers: headers })
                .then((res) => {
                    $('#modal_add_bank').modal('hide');
                    addBankBtn.disabled = false;
                    addBankBtn.querySelector('.btn-label').textContent = 'Thêm ngân hàng';
                })
                .catch((err) => {
                    console.log(err);
                })
        });
    })
</script>
