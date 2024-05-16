<?php $__env->startSection('title'); ?>
    Trang thống kê
<?php $__env->stopSection(); ?>
<?php $__env->startSection('header'); ?>
    <style>
        tr td {
            padding: 0.5rem !important;
            margin: 0 !important;
        }

        .select2-selection__choice {
            background-color: white !important;
        }

        .flatpickr-monthDropdown-months {
            max-width: 100px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_toolbar_container'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Khách hàng
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="<?php echo e(route('dashboard')); ?>" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Khách hàng</li>
                </ul>
            </div>
            <div class="d-flex align-items-center overflow-auto">
                <div class="d-flex align-items-center flex-shrink-0">
                    <span class="fs-7 fw-bold text-gray-700 flex-shrink-0 pe-4 d-none d-md-block">Lọc :</span>
                    <div class="flex-shrink-0">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active-color-primary btn-active-light active fw-semibold fs-7 px-4 me-1"
                                    data-bs-toggle="tab" href="">
                                    Toàn bộ
                                    <input type="radio" name="view_type" class="d-none" value="0" checked>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active-color-primary btn-active-light fw-semibold fs-7 px-4 me-1"
                                    data-bs-toggle="tab" href="#">
                                    7 ngày tới
                                    <input type="radio" name="view_type" class="d-none" value="1">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="bullet bg-secondary h-35px w-1px mx-5"></div>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container ">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" id="customer_search" class="form-control form-control-solid w-250px ps-12"
                                placeholder="Tìm kiếm" />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            
                            <button type="button" class="btn btn-primary btn-add-customer me-2" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_customer">Thêm khách hàng</button>
                            <button type="button" class="btn btn-primary btn-add-card" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_card">Thêm thẻ</button>
                        </div>
                        <div class="d-flex justify-content-end align-items-center d-none"
                            data-kt-customer-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-customer-table-select="selected_count"></span>Hàng được chọn
                            </div>
                            <button type="button" class="btn btn-danger"
                                data-kt-customer-table-select="delete_selected">Xóa khách hàng</button>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-reponsive align-middle table-row-dashed table-bordered fs-6 gy-5"
                        id="kt_customers_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true"
                                            data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="text-center min-w-125px">Tên - SĐT</th>
                                <th class="text-center min-w-125px">Ngân hàng</th>
                                <th class="text-center min-w-125px">Số thẻ</th>
                                <th class="text-center min-w-125px">Số tài khoản</th>
                                <th class="text-center min-w-125px">TT đăng nhập</th>
                                <th class="text-center min-w-125px">Chủ tài khoản</th>
                                <th class="text-center min-w-125px">Ngày đến hạn</th>
                                <th class="text-center min-w-70px">Nhắc nợ</th>
                                <th class="text-center min-w-100px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('customer.components.note', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('customer.components.remind', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('customer.modal.add', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('customer.modal.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('customer.modal.editCard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('customer.modal.add_card', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        var token = "<?php echo e(session('authToken')); ?>";
        var routes = {
            blankCards: "<?php echo e(route('api.card.blankCards')); ?>",
            storeCard: "<?php echo e(route('api.card.store')); ?>",
            remindCard: "<?php echo e(route('api.card.remindCard')); ?>",
            editCard: "<?php echo e(route('api.card.edit')); ?>",
            deleteCard: "<?php echo e(route('api.card.delete')); ?>",

            storeCustomer: "<?php echo e(route('api.customer.store')); ?>",
            updateCustomer: "<?php echo e(route('api.customer.update')); ?>",
            getAllCustomers: "<?php echo e(route('api.customer_showAll')); ?>",
            deleteCustomers: "<?php echo e(route('api.customer_delete')); ?>",
            updateCardNote: "<?php echo e(route('api.card.updateNote')); ?>",
        }
        var datatable;
    </script>
    <script src="<?php echo e(asset('assets/plugins/custom/datatables/datatables.bundle.js')); ?>"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
    <script src="<?php echo e(asset('assets/js/customer/list.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/customer/add.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/customer/add_card.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\CodeTools\laragon\www\WORK\quan_ly_cong_no\resources\views/customer/index.blade.php ENDPATH**/ ?>