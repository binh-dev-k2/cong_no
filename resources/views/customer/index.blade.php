@extends('layouts.layout')
@section('title')
    Khách hàng
@endsection
@section('header')
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
@endsection
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-4 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_toolbar_container'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-2 flex-column justify-content-center my-0">
                    Quản lý khách hàng
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
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
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card shadow-sm">
                <div class="card-header bg-light-primary">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center w-100 gap-3">
                        <div class="flex-grow-1">
                            <h3 class="card-title text-primary fw-bold fs-3 mb-0">
                                Danh sách khách hàng
                            </h3>
                            <p class="text-gray-600 mb-0 mt-2">Quản lý thông tin khách hàng và thẻ</p>
                        </div>
                        <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-3 flex-shrink-0">
                            <button type="button" id="btn-add-customer" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_customer">Thêm khách hàng</button>
                            <button type="button" id="btn-add-card" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_card">Thêm thẻ</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search Section -->
                    <div class="row mb-6">
                        <div class="col-lg-6 col-md-8 mb-3 mb-lg-0">
                            <div class="position-relative">
                                <i class="bi bi-search fs-4 position-absolute top-50 start-0 translate-middle-y ms-4 text-gray-500"></i>
                                <input type="text" id="customer_search" class="form-control form-control-lg ps-12"
                                    placeholder="Tìm kiếm khách hàng..." />
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center d-none mb-4"
                        data-kt-customer-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-customer-table-select="selected_count"></span>Hàng được chọn
                        </div>
                        <button type="button" class="btn btn-danger"
                            data-kt-customer-table-select="delete_selected">Xóa khách hàng</button>
                    </div>

                    <div class="table-responsive-lg">
                        <table class="table table-bordered table-hover align-middle fs-6" id="kt_customers_table">
                            <thead class="table-primary">
                                <tr class="text-start fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w-50px">STT</th>
                                    <th class="text-center min-w-125px">Tên - SĐT</th>
                                    <th class="text-center min-w-125px">Ngân hàng</th>
                                    <th class="text-center min-w-125px">Chủ tài khoản</th>
                                    <th class="text-center min-w-125px">Số thẻ</th>
                                    <th class="text-center min-w-125px">Số tài khoản</th>
                                    <th class="text-center min-w-125px">Ngày đến hạn</th>
                                    <th class="text-center min-w-100px">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-700">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('customer.modal.editCard')
    @include('customer.modal.edit')
@endsection

@section('script')
    <script>
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var routes = {
            blankCards: "{{ route('api.card.blankCards') }}",
            storeCard: "{{ route('api.card.store') }}",
            remindCard: "{{ route('api.card.remindCard') }}",
            editCard: "{{ route('api.card.edit') }}",
            deleteCard: "{{ route('api.card.delete') }}",

            storeCustomer: "{{ route('api.customer.store') }}",
            updateCustomer: "{{ route('api.customer.update') }}",
            getAllCustomers: "{{ route('api.customer_showAll') }}",
            deleteCustomers: "{{ route('api.customer_delete') }}",
            updateCardNote: "{{ route('api.card.updateNote') }}",
        }
        var datatable;
    </script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
    <script src="{{ asset('assets/js/customer/index.js') }}"></script>
@endsection

@section('modal')
    @include('customer.components.note')
    @include('customer.components.remind')
    @include('customer.components.login_info')
    @include('customer.modal.add')
    @include('customer.modal.add_card')
@endsection

