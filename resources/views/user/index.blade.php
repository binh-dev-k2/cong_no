@extends('layouts.layout')
@section('title')
    Người dùng
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
    <div id="kt_app_toolbar" class="app-toolbar py-4 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_toolbar_container'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">

                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-2 flex-column justify-content-center my-0">
                    Quản lý người dùng
                </h1>

                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Người dùng</li>
                </ul>
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
                                Danh sách người dùng
                            </h3>
                            <p class="text-gray-600 mb-0 mt-2">Quản lý tài khoản và quyền hạn người dùng</p>
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modal-user">Thêm người dùng</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search Section -->
                    <div class="row mb-6">
                        <div class="col-lg-6 col-md-8 mb-3 mb-lg-0">
                            <div class="position-relative">
                                <i class="bi bi-search fs-4 position-absolute top-50 start-0 translate-middle-y ms-4 text-gray-500"></i>
                                <input type="text" id="user_search" class="form-control form-control-lg ps-12"
                                    placeholder="Tìm kiếm người dùng..." />
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center d-none mb-4"
                        data-kt-customer-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-customer-table-select="selected_count"></span>Hàng được chọn
                    </div>
                </div>

                    <div class="table-responsive-md">
                        <table class="table table-bordered table-hover align-middle fs-6" id="user_table">
                            <thead class="table-primary">
                                <tr class="text-start fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-center min-w-125px">STT</th>
                                <th class="text-center min-w-125px">Tên</th>
                                <th class="text-center min-w-125px">Email</th>
                                <th class="text-center min-w-125px">Ngày tạo</th>
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

@section('modal')
    @include('user.modal.add')
    @include('user.modal.role')
@endsection
@endsection

@section('script')
<script>
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var routes = {
        datatable: "{{ route('api.user.datatable') }}",
        userDelete: "{{ route('api.user.delete') }}",
    }
    var datatable;
</script>

<script src="{{ asset('assets/js/user/index.js') }}"></script>
@endsection
