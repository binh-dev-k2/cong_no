@extends('layouts.layout')
@section('title')
Trang thống kê
@endsection
@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Thống kê</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Thống kê</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Row-->
                <div class="row gy-5 g-10">
                    <!--begin::Col-->
                    <div class="col-6">
                        <div class="card card-flush mb-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Tổng đang nợ</span>
                                    <span class="text-gray-500 pt-2 fw-semibold fs-6">Tổng số tiền khách hàng đang nợ</span>
                                </h3>
                                <div class="card-toolbar">
                                    <h3 class="align-content-center" id="totalMoney"></h3>
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body d-flex align-items-end pt-0">
                                <!--begin::Progress-->
                                <div class="d-flex align-items-center flex-column mt-3 w-100">
                                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                        <span class="fw-bolder fs-6 text-gray-900" id="isDoneDebit"></span>
                                        <span class="fw-bold fs-6 text-gray-500 " id="process-data-complete1"></span>
                                    </div>
                                    <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                        <div class="bg-success rounded h-8px"  id="process-data-complete2" role="progressbar" style="" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <!--end::Progress-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                        <!--begin::Table widget 1-->
                        <div class="card card-flush mb-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Nghiệp vụ hiện tại</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h2 class="card-title mb-0">
                                        <span class="card-label fw-bold text-gray-900" id="totalBusiness"></span>
                                    </h2>
                                    <a href="{{ route('business') }}" class="btn btn-outline-success">Xem chi tiết</a>
                                </div>
                            </div>


                            <!--end: Card Body-->
                        </div>
                        <!--end::Table widget 1-->
                    </div>
                    <!--end::Col-->
                    <div class="col-6">
                        <!--begin::Chart widget 3-->
                        <div class="card card-flush overflow-hidden h-100">
                            <!--begin::Header-->
                            <div class="card-header py-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Tổng khách hàng</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Tổng số khách hàng sắp đến hạn trong 7 ngày tới</span>
                                </h3>
                                <div class="card-toolbar">
                                    <h3 class="align-content-center" id="totalCustomers"></h3>
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                <!--begin::Chart-->
                                <div class="d-flex flex-center me-5 pt-2">
                                    <canvas id="kt_card_widget_4_chart" class="mh-400px"></canvas>
                                </div>
                                <!--end::Chart-->
                                <!--begin::Labels-->

                                <!--end::Labels-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Chart widget 3-->
                    </div>
                </div>
                <!--end::Row-->
                <!--begin::Row-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>
@endsection
@section('script')
<script>
    let token = "{{ session('authToken')}}";
    let routes = {
        getDonutChartData: "{{ route('api.dashboard.getDonutChartData') }}",
        getProcessData: "{{ route('api.dashboard.getProcessData') }}",
        getTotalBusiness: "{{ route('api.dashboard.getTotalBusiness') }}"
    }
    var datatable;
</script>

<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/dashBoardChart.js') }}"></script>
<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
<script src="assets/plugins/global/plugins.bundle.js"></script>
@endsection

