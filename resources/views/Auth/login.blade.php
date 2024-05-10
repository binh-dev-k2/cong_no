@extends('layouts.auth')
@section('title')
    Đăng nhập tài khoản
@endsection
@section('content')
    <div class="d-flex flex-center flex-column flex-lg-row-fluid">
        <div class="w-lg-500px p-10">

            <form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="text-center mb-11">
                    <h1 class="text-gray-900 fw-bolder mb-3">
                        Đăng nhập
                    </h1>
                    <div class="text-gray-500 fw-semibold fs-6">
                        Đăng nhập vào hệ thống ngay
                    </div>
                </div>
                <div class="fv-row mb-8">
                    <input type="text" placeholder="Email" name="email" autocomplete="off"
                        class="form-control bg-transparent @error('email') is-invalid @enderror" autofocus />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="fv-row mb-3">
                    <input type="password" placeholder="Password" name="password" autocomplete="off"
                        class="form-control bg-transparent @error('password') is-invalid @enderror" />
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                {{-- <div class="row mb-3 justify-content-end">
                    <div class="w-auto ">
                        <div class="form-check ">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Nhớ mật khẩu') }}
                            </label>
                        </div>
                    </div>
                </div> --}}
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                </div>
                <div class="d-grid mb-10">
                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                        <span class="indicator-label">Đăng nhập</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
