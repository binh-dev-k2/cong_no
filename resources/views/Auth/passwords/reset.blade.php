@extends('layouts.layout')
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_toolbar_container'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">

                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Đổi mật khẩu
                </h1>

                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Đổi mật khẩu</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container ">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('profile.updatePassword') }}">
                        @csrf
                        <div class="d-flex flex-column mb-3 fv-row">
                            <label class="fs-6 fw-semibold mb-2" for="current_password">Mật khẩu hiện tại</label>
                            <input id="current_password" type="password"
                                class="form-control form-control-solid @error('current_password') is-invalid @enderror"
                                name="current_password" required>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex flex-column mb-3 fv-row">
                            <label class="fs-6 fw-semibold mb-2" for="password">Mật khẩu mới</label>
                            <input id="password" type="password"
                                class="form-control form-control-solid @error('password') is-invalid @enderror"
                                name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex flex-column mb-3 fv-row">
                            <label class="fs-6 fw-semibold mb-2" for="password_confirmation">Nhập lại mật khẩu</label>
                            <input id="password_confirmation" type="password"
                                class="form-control form-control-solid @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation" required>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Xác nhận') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
