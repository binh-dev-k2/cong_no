<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="<?php echo e(route('dashboard')); ?>">
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
                        <a class="menu-link  <?php if(Route::currentRouteName() == 'dashboard'): ?> active <?php endif; ?>" href="<?php echo e(route('dashboard')); ?>">
                            <span class="menu-icon">
                                <i class="ki-outline ki-abstract-13 fs-2"></i>
                            </span>
                            <span class="menu-title">Thống kê</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link <?php if(Route::currentRouteName() == 'customer'): ?> active <?php endif; ?>" href="<?php echo e(route('customer')); ?>">
                            <span class="menu-icon">
                                <i class="ki-outline ki-user fs-2"></i>
                            </span>
                            <span class="menu-title">Khách hàng</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link <?php if(Route::currentRouteName() == 'business'): ?> active <?php endif; ?>" href="<?php echo e(route('business')); ?>">
                            <span class="menu-icon">
                                <i class="ki-outline ki-user fs-2"></i>
                            </span>
                            <span class="menu-title">Nghiệp vụ</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<?php /**PATH D:\CodeTools\laragon\www\WORK\quan_ly_cong_no\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>