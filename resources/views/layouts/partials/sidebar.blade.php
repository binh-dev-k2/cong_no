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

                </div>
            </div>
        </div>
    </div>
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <a href="{{ route('update-code') }}"
            class="btn btn-flex flex-center btn-custom btn-warning overflow-hidden text-nowrap px-0 h-40px w-100 btn-update-code"
            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
            title="Cập nhật phiên bản mới">
            <span class="btn-label">Cập nhật</span>
            <span class="indicator-progress d-none">
                Loading... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
            <i class="ki-outline ki-refresh btn-icon fs-2 m-0"></i>
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateCodeBtn = document.querySelector('.btn-update-code');
        updateCodeBtn.addEventListener('click', function(e) {
            updateCodeBtn.disabled = true;
            const loadingIndicator = updateCodeBtn.querySelector('.indicator-progress');
            loadingIndicator.classList.remove('d-none');
        });
    })
</script>
