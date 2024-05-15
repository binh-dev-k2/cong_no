@extends('layouts.layout')
@section('title')
    Trang thống kê
@endsection
@section('header')
    <style>
        tr td {
            padding: 0.5rem !important;
            margin: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_toolbar_container'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">

                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Nghiệp vụ
                </h1>

                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Nghiệp vụ</li>
                </ul>
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
                            <input type="text" id="business_search" class="form-control form-control-solid w-250px ps-12"
                                placeholder="Tìm kiếm" />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <button type="button" class="btn btn-primary btn-add-customer" data-bs-toggle="modal"
                                data-bs-target="#modal_business">Thêm nghiệp vụ</button>
                        </div>
                        <div class="d-flex justify-content-end align-items-center d-none"
                            data-kt-customer-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-customer-table-select="selected_count"></span>Hàng được chọn
                            </div>
                            <button type="button" class="btn btn-danger"
                                data-kt-customer-table-select="delete_selected">Xóa</button>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table
                        class="table table-reponsive align-middle table-striped table-row-dashed table-bordered fs-6 gy-5"
                        id="business_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                {{-- <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true"
                                            data-kt-check-target="#business_table .form-check-input" value="1" />
                                    </div>
                                </th> --}}
                                <th class="text-center min-w-125px">Ngày tạo</th>
                                <th class="text-center min-w-125px">Tên - SĐT</th>
                                <th class="text-center min-w-125px">Số thẻ</th>
                                <th class="text-center min-w-50px">Phí (%)</th>
                                <th class="text-center min-w-125px">Số tiền(vnđ)</th>
                                <th class="text-center min-w-75px">Hình thức</th>
                                <th class="text-center min-w-125px">Phí Đáo/Rút(vnđ)</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Trả thêm(vnđ)</th>
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
    {{--
        @include('customer.components.note')
        --}}

    <div class="modal fade" id="money-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">

        </div>
    </div>
@endsection

@section('modal')
    @include('business.modal.business')
@endsection

@section('script')
    <script>
        var token = "{{ session('authToken') }}";
        var routes = {
            datatable: "{{ route('api.business.datatable') }}",
            businessComplete: "{{ route('api.business.complete') }}",
            businessUpdatePayExtra: "{{ route('api.business.updatePayExtra') }}",
            // businessViewMoney: "{{ route('api.business.viewMoney') }}",
            businessUpdateBusinessMoney: "{{ route('api.business.updateBusinessMoney') }}",
        }
        var datatable;
    </script>

    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/business/list.js') }}"></script>
@endsection
