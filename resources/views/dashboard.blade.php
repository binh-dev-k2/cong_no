@extends('layouts.layout')
@section('title')
    Trang thống kê
@endsection
@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                            Thống kê</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <li class="breadcrumb-item text-muted">Thống kê</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <div class="row gy-5 g-10">
                        <div class="col-12 col-md-6">
                            <div class="card card-flush mb-10">
                                <div class="card-header pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Ghi nợ</span>
                                        <span class="text-gray-500 pt-2 fw-semibold fs-6">Tổng số tiền khách hàng đang
                                            nợ</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <h3 class="align-content-center" id="total-debit"></h3>
                                    </div>
                                </div>
                                <div class="card-body d-flex align-items-end pt-0">
                                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                                        <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                            <span class="fw-bolder fs-6 text-gray-900" id="count-paid-debit"></span>
                                            <span class="fw-bold fs-6 text-gray-500 " id="process-data-complete1"></span>
                                        </div>
                                        <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                            <div class="bg-success rounded h-8px" id="process-data-complete2"
                                                role="progressbar" style="" aria-valuenow="50" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-end align-items-center mb-1">
                                        <a href="{{ route('debit') }}" class="btn btn-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-flush mb-10">
                                <div class="card-header pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Nghiệp vụ</span>
                                        <span class="text-gray-500 pt-2 fw-semibold fs-6">Số lượng nghiệp vụ hiện tại</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <h3 class="align-content-center" id="total-business"></h3>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-end align-items-center mb-1">
                                        <a href="{{ route('business') }}" class="btn btn-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card card-flush overflow-hidden h-100">
                                <div class="card-header py-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Khách hàng</span>
                                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Số lượng khách hàng sắp đến hạn
                                            trong 7 ngày tới</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <h3 class="align-content-center" id="chart-customer"></h3>
                                    </div>
                                </div>
                                <div class="card-body py-0">
                                    <div class="d-flex flex-center">
                                        <canvas id="canvas-chart-customer" class="mh-300px" width="300" height="300"></canvas>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center mb-1">
                                        <a href="{{ route('customer') }}" class="btn btn-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card card-flush">
                                <div class="card-header pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Thẻ hết hạn</span>
                                        <span class="text-gray-500 pt-2 fw-semibold fs-6">Danh sách thẻ đã hết hạn</span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-reponsive align-middle table-row-dashed table-bordered fs-6 gy-5" id="table-card-expired">
                                            <thead>
                                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="text-center min-w-125px">STT</th>
                                                    <th class="text-center min-w-125px">Khách hàng</th>
                                                    <th class="text-center min-w-125px">Số thẻ</th>
                                                    <th class="text-center min-w-125px">Hết hạn</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let routes = {
            getChartCustomer: "{{ route('api.dashboard.getChartCustomer') }}",
            getTotalDebit: "{{ route('api.dashboard.getTotalDebit') }}",
            getTotalBusiness: "{{ route('api.dashboard.getTotalBusiness') }}",
            getCardExpired: "{{ route('api.dashboard.getCardExpired') }}",
        }
    </script>
    <script src="{{ asset('assets/js/dashboard/dashboardChart.js') }}"></script>
@endsection
