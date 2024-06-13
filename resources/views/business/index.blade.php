@extends('layouts.layout')
@section('title')
    Nghiệp vụ
@endsection
@section('header')
    <style>
        tr td {
            padding: 0.5rem !important;
            margin: 0 !important;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
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
                            <button type="button" class="btn btn-warning me-2 btn-edit-setting">Sửa tiền chia</button>
                            <button type="button" class="btn btn-primary btn-add-customer" data-bs-toggle="modal"
                                data-bs-target="#modal-add">Thêm nghiệp vụ</button>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-reponsive align-middle table-row-dashed table-bordered fs-6 gy-5"
                        id="business_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                {{-- <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true"
                                            data-kt-check-target="#business_table .form-check-input" value="1" />
                                    </div>
                                </th> --}}
                                <th class="text-center min-w-75px">Ngày tạo</th>
                                <th class="text-center min-w-125px">Chủ thẻ/Khách</th>
                                <th class="text-center min-w-125px">Số thẻ</th>
                                <th class="text-center min-w-50px">Phí (%)</th>
                                <th class="text-center min-w-125px">Số tiền(vnđ)</th>
                                <th class="text-center min-w-75px">Hình thức</th>
                                <th class="text-center">Phí</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                {{-- <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                <th class="text-center min-w-125px">Tiền - Ghi chú</th> --}}
                                <th class="text-center min-w-125px">Trả thêm</th>
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

    <div class="modal fade" id="modal-edit-setting" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">

        </div>
    </div>
@endsection

@section('modal')
    @include('business.modal.add')
    @include('business.modal.edit')
@endsection

@section('script')
    <script>
        var token = "{{ session('authToken') }}";
        var routes = {
            datatable: "{{ route('api.business.datatable') }}",
            businessComplete: "{{ route('api.business.complete') }}",
            businessUpdatePayExtra: "{{ route('api.business.updatePayExtra') }}",
            businessDelete: "{{ route('api.business.delete') }}",
            // businessViewMoney: "{{ route('api.business.viewMoney') }}",
            businessUpdateBusinessMoney: "{{ route('api.business.updateBusinessMoney') }}",
            businessEditSetting: "{{ route('api.business.editSetting') }}",
            businessUpdateSetting: "{{ route('api.business.updateSetting') }}",
        }
        var datatable;
    </script>

    <script src="{{ asset('assets/js/business/list.js') }}"></script>
@endsection
