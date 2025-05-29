@extends('layouts.layout')
@section('title')
    Nghiệp vụ
@endsection
@section('header')
    <style>
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

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .search-results li:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-4 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-2 flex-column justify-content-center my-0">
                    Quản lý nghiệp vụ
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

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!-- Business Note Card -->
            <div class="card shadow-sm mb-8">
                <div class="card-header py-2">
                    <h3 class="card-title fw-bold fs-3">
                        Ghi chú nghiệp vụ
                    </h3>
                </div>
                <div class="card-body">
                    <textarea class="form-control" name="business_note" id="business_note" rows="2"
                        placeholder="Nhập thông báo cho nghiệp vụ...">{{ $businessNote->value }}</textarea>
                </div>
            </div>

            <!-- Main Business Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-success">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center w-100 gap-3">
                        <div class="flex-grow-1">
                            <h3 class="card-title text-white fw-bold fs-3 mb-0">
                                Danh sách nghiệp vụ
                            </h3>
                            <p class="text-white-75 mb-0 mt-2">Quản lý tất cả các nghiệp vụ trong hệ thống</p>
                        </div>
                        <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-3 flex-shrink-0">
                            <button type="button" class="btn btn-light-warning btn-edit-setting">
                                Sửa tiền chia
                            </button>
                            <button type="button" class="btn btn-light-success btn-add-customer" data-bs-toggle="modal"
                                data-bs-target="#modal-add">
                                Thêm nghiệp vụ
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search Section -->
                    <div class="row mb-6">
                        <div class="col-lg-6 col-md-8 mb-3 mb-lg-0">
                            <div class="position-relative">
                                <i
                                    class="bi bi-search fs-4 position-absolute top-50 start-0 translate-middle-y ms-4 text-gray-500"></i>
                                <input type="text" id="business_search" class="form-control form-control-lg ps-12"
                                    placeholder="Tìm kiếm nghiệp vụ..." />
                            </div>
                        </div>
                    </div>

                    <!-- Business Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle fs-6" id="business_table">
                            <thead class="table-success">
                                <tr class="text-start fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w-75px">Ngày tạo</th>
                                    <th class="text-center min-w-125px">Chủ thẻ/Khách</th>
                                    <th class="text-center min-w-125px">Số thẻ</th>
                                    <th class="text-center min-w-50px">Phí (%)</th>
                                    <th class="text-center min-w-100px">Số tiền</th>
                                    <th class="text-center min-w-75px">Hình thức</th>
                                    <th class="text-center">Phí</th>
                                    <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                    <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                    <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                    <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                    <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                    <th class="text-center min-w-125px">Tiền - Ghi chú</th>
                                    <th class="text-center min-w-125px">Trả thêm</th>
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

    <!-- Modal Container -->
    <div class="modal fade" id="modal-edit-setting" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
        </div>
    </div>
@endsection

@section('modal')
    @include('business.components.modal-add')
    @include('business.components.modal-edit')
@endsection

@section('script')
    <script>
        // Global variables
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var routes = {
            datatable: "{{ route('api.business.datatable') }}",
            businessComplete: "{{ route('api.business.complete') }}",
            businessUpdatePayExtra: "{{ route('api.business.updatePayExtra') }}",
            businessDelete: "{{ route('api.business.delete') }}",
            businessUpdateBusinessMoney: "{{ route('api.business.updateBusinessMoney') }}",
            businessEditSetting: "{{ route('api.business.editSetting') }}",
            businessUpdateNote: "{{ route('api.business.updateNote') }}",
            businessStore: "{{ route('api.business.store') }}",
            businessUpdate: "{{ route('api.business.update') }}",
            cardFind: "{{ route('api.card.find') }}"
        }
        var datatable;
        var allMachines = @json($machines);
        var collaborators = @json($collaborators);
        var businessMoneys = @json($businessMoneys);
    </script>

    <script src="{{ asset('assets/js/business/list.js') }}"></script>
@endsection
