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
                            <div class="card card-flush overflow-hidden h-100 shadow-sm hover-elevate-up">
                                <div class="card-header py-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Dòng tiền</span>
                                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Tổng quan về tình hình tài
                                            chính</span>
                                        @if (now()->lt('2025-06-01'))
                                            <span class="text-danger mt-1 fs-7">Tính năng sẽ được khởi chạy vào ngày
                                                01/06/2025, toàn bộ dữ liệu được lưu trước ngày này sẽ được bỏ qua.</span>
                                        @endif
                                    </h3>
                                    <div class="card-toolbar">
                                        <h3 class="align-content-center text-primary" id="total-investment">0 VNĐ</h3>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column py-2">
                                    @if (now()->gte('2025-06-01'))
                                        <button class="btn btn-primary btn-sm" id="btn-update-total-investment"
                                            data-bs-toggle="modal" data-bs-target="#modal-update-total-investment">
                                            <i class="fas fa-plus me-2"></i>Thêm quỹ
                                        </button>
                                    @endif

                                    <div class="d-flex flex-column gap-5 mt-5">
                                        <div
                                            class="d-flex justify-content-between align-items-center p-3 bg-light-primary rounded">
                                            <span class="fw-bold fs-6 text-gray-700">Tiền nghiệp vụ:</span>
                                            <span class="fw-bold fs-6 text-primary" id="total-business-value">0 VNĐ</span>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-center p-3 bg-light-warning rounded">
                                            <span class="fw-bold fs-6 text-gray-700">Tiền ghi nợ:</span>
                                            <span class="fw-bold fs-6 text-warning" id="total-debt-value">0 VNĐ</span>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between align-items-center p-3 bg-light-success rounded">
                                            <span class="fw-bold fs-6 text-gray-700">Lãi máy tháng này:</span>
                                            <span class="fw-bold fs-6 text-success" id="total-interest-machine-value">0
                                                VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="card card-flush overflow-hidden h-100 shadow-sm hover-elevate-up">
                                <div class="card-header py-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Khách hàng</span>
                                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Số lượng khách hàng sắp đến hạn
                                            trong 7 ngày tới</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <h3 class="align-content-center text-primary" id="chart-customer"></h3>
                                    </div>
                                </div>
                                <div class="card-body py-0">
                                    <div class="d-flex flex-center">
                                        <canvas id="canvas-chart-customer" class="mh-300px" width="300"
                                            height="300"></canvas>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center mb-1 py-3">
                                        <a href="{{ route('customer') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row g-5">
                                <!-- Card Ghi nợ -->
                                <div class="col-md-4">
                                    <div class="card card-flush h-100 shadow-sm hover-elevate-up">
                                        <div class="card-header pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-gray-900">Ghi nợ</span>
                                                <span class="text-gray-500 pt-2 fw-semibold fs-6">
                                                    Tổng số tiền khách hàng đang nợ
                                                </span>
                                            </h3>
                                            <div class="card-toolbar">
                                                <h3 class="align-content-center text-primary" id="total-debit"></h3>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex align-items-end pt-0">
                                            <div class="d-flex align-items-center flex-column mt-3 w-100">
                                                <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                                    <span class="fw-bolder fs-6 text-gray-900" id="count-paid-debit"></span>
                                                    <span class="fw-bold fs-6 text-gray-500"
                                                        id="process-data-complete1"></span>
                                                </div>
                                                <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                                    <div class="bg-success rounded h-8px" id="process-data-complete2"
                                                        role="progressbar" style="" aria-valuenow="50"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex flex-column justify-content-end py-2 mb-3">
                                            <a href="{{ route('debit') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Nghiệp vụ -->
                                <div class="col-md-4">
                                    <div class="card card-flush h-100 shadow-sm hover-elevate-up">
                                        <div class="card-header pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-gray-900">Nghiệp vụ</span>
                                                <span class="text-gray-500 pt-2 fw-semibold fs-6">
                                                    Số lượng nghiệp vụ hiện tại
                                                </span>
                                            </h3>
                                            <div class="card-toolbar">
                                                <h3 class="align-content-center text-primary" id="total-business"></h3>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex flex-column justify-content-end py-2 mb-3">
                                            <a href="{{ route('business') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Máy -->
                                <div class="col-md-4">
                                    <div class="card card-flush h-100 shadow-sm hover-elevate-up">
                                        <div class="card-header pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-gray-900">Máy</span>
                                                <span class="text-gray-500 pt-2 fw-semibold fs-6">Phí máy</span>
                                                <div class="d-flex flex-column mt-3">
                                                    <div class="d-flex gap-2">
                                                        <select class="form-select form-select-solid" name="month"
                                                            id="machine-month-select">
                                                            <option value="">Tháng</option>
                                                            @for ($i = 1; $i <= 12; $i++)
                                                                <option value="{{ $i }}">{{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <select class="form-select form-select-solid" name="year"
                                                            id="machine-year-select">
                                                            <option value="">Toàn bộ</option>
                                                            @for ($i = now()->year; $i >= 2025; $i--)
                                                                <option value="{{ $i }}"
                                                                    {{ $i == now()->year ? 'selected' : '' }}>
                                                                    {{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </h3>
                                            <div class="card-toolbar">
                                                <h3 class="align-content-center text-primary" id="total-machine-fee"></h3>
                                            </div>
                                        </div>
                                        <div class="card-body d-flex flex-column justify-content-end py-2 mb-3">
                                            <a href="{{ route('machine') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card card-flush shadow-sm hover-elevate-up">
                                <div class="card-header pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold text-gray-900">Thẻ hết hạn</span>
                                        <span class="text-gray-500 pt-2 fw-semibold fs-6">Danh sách thẻ đã hết hạn</span>
                                    </h3>
                                    {{-- <div class="card-toolbar">
                                        <div class="d-flex align-items-center position-relative">
                                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <input type="text" class="form-control form-control-solid w-250px ps-12"
                                                placeholder="Tìm kiếm..." id="search-card-expired">
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3"
                                            id="table-card-expired">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-50px text-center">STT</th>
                                                    <th class="min-w-150px text-center">Khách hàng</th>
                                                    <th class="min-w-150px text-center">Số thẻ</th>
                                                    <th class="min-w-100px text-center">Ngân hàng</th>
                                                    <th class="min-w-100px text-center">Hết hạn</th>
                                                    <th class="min-w-100px text-center">Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
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
