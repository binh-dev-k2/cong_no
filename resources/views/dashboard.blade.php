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
                            <div class="card card-flush overflow-hidden h-100">
                                <div class="card-header py-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Dòng tiền</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <h3 class="align-content-center" id="total-investment">0 VNĐ</h3>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column py-2">
                                    <button class="btn btn-primary" id="btn-update-total-investment" data-bs-toggle="modal"
                                        data-bs-target="#modal-update-total-investment">Thêm quỹ</button>

                                    <div class="d-flex flex-column gap-5 mt-5">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold fs-6 text-gray-500">Tiền nghiệp vụ:</span>
                                            <span class="fw-bold fs-6 text-gray-900" id="total-business-value">0 VNĐ</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold fs-6 text-gray-500">Tiền ghi nợ:</span>
                                            <span class="fw-bold fs-6 text-gray-900" id="total-debt-value">0 VNĐ</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold fs-6 text-gray-500">Lãi máy tháng này:</span>
                                            <span class="fw-bold fs-6 text-gray-900" id="total-interest-machine-value">0
                                                VNĐ</span>
                                        </div>
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
                                        <canvas id="canvas-chart-customer" class="mh-300px" width="300"
                                            height="300"></canvas>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center mb-1">
                                        <a href="{{ route('customer') }}" class="btn btn-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card card-flush mb-10" style="min-height: 230px">
                                        <div class="card-header pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-gray-900">Ghi nợ</span>
                                                <span class="text-gray-500 pt-2 fw-semibold fs-6">
                                                    Tổng số tiền khách hàng đang nợ
                                                </span>
                                            </h3>
                                            <div class="card-toolbar">
                                                <h3 class="align-content-center" id="total-debit"></h3>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex align-items-end pt-0">
                                            <div class="d-flex align-items-center flex-column mt-3 w-100">
                                                <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                                    <span class="fw-bolder fs-6 text-gray-900" id="count-paid-debit"></span>
                                                    <span class="fw-bold fs-6 text-gray-500 "
                                                        id="process-data-complete1"></span>
                                                </div>
                                                <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                                    <div class="bg-success rounded h-8px" id="process-data-complete2"
                                                        role="progressbar" style="" aria-valuenow="50"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex flex-column py-2">
                                            <div class="d-flex justify-content-end align-items-center mb-1">
                                                <a href="{{ route('debit') }}" class="btn btn-primary">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card card-flush mb-10" style="min-height: 230px">
                                        <div class="card-header pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-gray-900">Nghiệp vụ</span>
                                                <span class="text-gray-500 pt-2 fw-semibold fs-6">Số lượng nghiệp vụ hiện
                                                    tại</span>
                                            </h3>
                                            <div class="card-toolbar">
                                                <h3 class="align-content-center" id="total-business"></h3>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex flex-column py-2">
                                            <div class="d-flex justify-content-end align-items-center mb-1">
                                                <a href="{{ route('business') }}" class="btn btn-primary">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card card-flush" style="min-height: 230px">
                                        <div class="card-header pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-gray-900">Máy</span>
                                                <span class="text-gray-500 pt-2 fw-semibold fs-6">Phí máy</span>
                                                <div class="d-flex flex-column mt-3">
                                                    <div class="d-flex">
                                                        <select class="form-select form-select-solid me-2" name="month"
                                                            id="machine-month-select">
                                                            <option value="">Tháng</option>
                                                            @for ($i = 1; $i <= 12; $i++)
                                                                <option value="{{ $i }}">{{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <select class="form-select form-select-solid min-w-100px"
                                                            name="year" id="machine-year-select">
                                                            <option value="">Năm</option>
                                                            @for ($i = now()->year; $i >= 2025; $i--)
                                                                <option value="{{ $i }}">{{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </h3>
                                            <div class="card-toolbar">
                                                <h3 class="align-content-center" id="total-machine-fee"></h3>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex flex-column py-2">
                                            <div class="d-flex justify-content-end align-items-center mb-1">
                                                <a href="{{ route('machine') }}" class="btn btn-primary">Xem chi tiết</a>
                                            </div>
                                        </div>
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
                                        <table
                                            class="table table-reponsive align-middle table-row-dashed table-bordered fs-6 gy-5"
                                            id="table-card-expired">
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

    <div class="modal fade" id="modal-update-total-investment" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-update-total-investment-title">Thêm quỹ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-update-total-investment">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="total-investment" class="form-label">Tổng đầu tư</label>
                            <input data-type="money" type="text" class="form-control" id="investment-value"
                                name="value" value="0">
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </div>
                </form>
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
            getMachineFee: "{{ route('api.dashboard.getMachineFee') }}",
            getTotalInvestment: "{{ route('api.dashboard.getTotalInvestment') }}",
            updateTotalInvestment: "{{ route('api.dashboard.updateTotalInvestment') }}",
        }
    </script>
    <script src="{{ asset('assets/js/dashboard/dashboardChart.js') }}"></script>
@endsection
