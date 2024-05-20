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
    <link href="assets/css/debit.css" rel="stylesheet" type="text/css" />
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
                    <li class="breadcrumb-item text-muted">Ghi nợ</li>
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
                            <input type="text" id="debit_search" class="form-control form-control-solid w-250px ps-12 "
                                data-kt-debit-table-filter="search" placeholder="Tìm kiếm" />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-reponsive align-middle table-row-dashed table-bordered fs-6 gy-5"
                        id="kt_debit_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-center min-w-125px">Chủ thẻ/Khách</th>
                                <th class="text-center min-w-125px">Số thẻ</th>
                                <th class="text-center min-w-125px">Hình thức</th>
                                <th class="text-center min-w-50px">Phí</th>
                                <th class="text-center min-w-125px">Tiền trả thêm</th>
                                <th class="text-center min-w-125px">Tổng số tiền</th>
                                <th class="text-center min-w-125px">Trạng thái</th>
                                <th class="text-center min-w-70px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <h3>Tổng tiền: <?= number_format($totalMoney, 0, ',', ',') ?> VNĐ </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="money-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">

        </div>
    </div>
@endsection


@section('script')
    <script>
        let token = "{{ session('authToken') }}";
        let routes = {
            getAllDebitCards: "{{ route('api.debit_showAll') }}",
            updateDebitStatus: "{{ route('api.debit_updateStatus') }}",
        }
        var datatable;
    </script>


    <script src="{{ asset('assets/js/debit/list.js') }}"></script>
@endsection
