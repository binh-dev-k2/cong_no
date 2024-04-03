@extends('layouts.layout')
@section('title')
    Trang thống kê
@endsection
@section('header')
@endsection

@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_toolbar_container'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Khách hàng
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Khách hàng</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Action group-->
            <div class="d-flex align-items-center overflow-auto">
                <!--begin::Wrapper-->
                <div class="d-flex align-items-center flex-shrink-0">
                    <!--begin::Label-->
                    <span class="fs-7 fw-bold text-gray-700 flex-shrink-0 pe-4 d-none d-md-block">Lọc :</span>
                    <!--end::Label-->
                    <div class="flex-shrink-0">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active-color-primary btn-active-light active fw-semibold fs-7 px-4 me-1"
                                    data-bs-toggle="tab" href="#">7 ngày tới</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active-color-primary btn-active-light fw-semibold fs-7 px-4 me-1"
                                    data-bs-toggle="tab" href="">15 ngày tới</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active-color-primary btn-active-light fw-semibold fs-7 px-4"
                                    data-bs-toggle="tab" href="#">30 ngày tới</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--end::Wrapper-->
                <!--begin::Separartor-->
                <div class="bullet bg-secondary h-35px w-1px mx-5"></div>
                <!--end::Separartor-->
                <!--begin::Wrapper-->
                <div class="d-flex align-items-center">
                    <!--begin::Label-->
                    <span class="fs-7 fw-bold text-gray-700 flex-shrink-0 pe-4 d-none d-md-block">Sắp xếp:</span>
                    <!--end::Label-->
                    <!--begin::Select-->
                    <select class="form-select form-select-sm w-md-125px form-select-solid" data-control="select2"
                        data-placeholder="Latest" data-hide-search="true">
                        <option value=""></option>
                        <option value="1" selected="selected">Latest</option>
                        <option value="2">In Progress</option>
                        <option value="3">Done</option>
                    </select>
                    <!--end::Select-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Action group-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container ">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-customer-table-filter="search"
                                class="form-control form-control-solid w-250px ps-12" placeholder="Tìm kiếm" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <!--begin::Export-->
                            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                data-bs-target="#kt_customers_export_modal">
                                <i class="ki-duotone ki-exit-up fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>Export</button>
                            <!--end::Export-->
                            <!--begin::Add customer-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_customer">Thêm khách hàng</button>
                            <!--end::Add customer-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none"
                            data-kt-customer-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected
                            </div>
                            <button type="button" class="btn btn-danger"
                                data-kt-customer-table-select="delete_selected">Delete Selected</button>
                        </div>
                        <!--end::Group actions-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true"
                                            data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-125px">Customer Name</th>
                                <th class="min-w-125px">Email</th>
                                <th class="min-w-125px">Company</th>
                                <th class="min-w-125px">Payment Method</th>
                                <th class="min-w-125px">Created Date</th>
                                <th class="text-end min-w-70px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <a href="apps/customers/view.html" class="text-gray-800 text-hover-primary mb-1">Emma
                                        Smith</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-600 text-hover-primary mb-1">smith@kpmg.com</a>
                                </td>
                                <td>-</td>
                                <td data-filter="mastercard">
                                    <img src="assets/media/svg/card-logos/mastercard.svg" class="w-35px me-3"
                                        alt="" />**** 4118
                                </td>
                                <td>14 Dec 2020, 8:43 pm</td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="apps/customers/view.html" class="menu-link px-3">View</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3"
                                                data-kt-customer-table-filter="delete_row">Delete</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
    <template id="template_card">
        <div class="d-flex align-items-center mb-7">
            <!--begin::Avatar-->
            <div class="symbol symbol-50px me-5">
                <img src="/metronic8/demo1/assets/media/avatars/300-6.jpg" class="img-fluid" style="object-fit: contain" alt="">
            </div>
            <!--end::Avatar-->

            <!--begin::Text-->
            <div class="flex-grow-1 d-flex justify-content-between">
               <div>
                 <p href="#" class="text-gray-900 fw-bold text-hover-primary fs-6 account_name text-uppercase"></p>
                <input type="hidden" name="card[]" class="card_added_input">
                <span class="text-muted d-block fw-bold card_number">09128822738732</span>
               </div>
               <div>
                <p   class="btn btn-danger hover-scale btn_delete">Xóa</p>
               </div>
            </div>
    </template>
@endsection
@section('script')
    <script>
        var token = "{{ session('authToken') }}";
        var find_card_route = "{{ route('api.card_find') }}"
        var store_card_route = "{{ route('api.card_store') }}"
        var add_customer_route = "{{ route('api.customer_store') }}"
        var list_card = [];
        var changeListCardEvent = new CustomEvent('changeListCardEvent', {});
    </script>
    <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>

    <script src="assets/js/widgets.bundle.js"></script>
    <script src="assets/js/custom/widgets.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>

    <script src="assets/js/custom/apps/customers/list/list.js"></script>
    <script src="assets/js/custom/apps/customers/add.js"></script>
    <script src="assets/js/custom/apps/customers/add_card.js"></script>
@endsection
@section('modals')
    @include('admin.customer.modal.add')
@endsection
