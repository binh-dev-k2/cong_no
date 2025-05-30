@extends('layouts.layout')
@section('title')
    Vai trò
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
                    Quản lý vai trò
                </h1>

                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Vai trò</li>
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
                                Danh sách vai trò
                            </h3>
                            <p class="text-gray-600 mb-0 mt-2">Quản lý vai trò và quyền hạn hệ thống</p>
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#role-modal">Thêm vai trò</button>
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
                                    placeholder="Tìm kiếm vai trò..." />
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive-md">
                        <table class="table table-bordered table-hover align-middle fs-6" id="role-table">
                            <thead class="table-primary">
                                <tr class="text-start fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-center min-w-125px">STT</th>
                                <th class="text-center min-w-125px">Tên</th>
                                <th class="text-center min-w-125px">Quyền hạn</th>
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

    @include('role.modal')
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const notify = (text, type = 'success', showCancelButton = false) => {
                return Swal.fire({
                    text: text,
                    icon: type,
                    buttonsStyling: !1,
                    showCancelButton: showCancelButton,
                    confirmButtonText: "Xác nhận",
                    cancelButtonText: "Đóng",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-light"
                    }
                })
            }

            const datatable = $("#role-table").DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('api.role.list') }}",
                    type: "POST",
                    beforeSend: function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", document.querySelector(
                            'meta[name="csrf-token"]').getAttribute('content'));
                    },
                    data: function(d) {
                        d.search = $('#role-search').val();
                    }
                },
                columnDefs: [{
                        targets: 0,
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return `<span>${meta.row + meta.settings._iDisplayStart + 1}</span>`
                        }
                    },
                    {
                        targets: 1,
                        data: 'name',
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<span>${data ?? ''}</span>`
                        }
                    },
                    {
                        targets: 2,
                        data: 'permissions',
                        orderable: false,
                        className: 'min-w-150px',
                        render: function(data, type, row) {
                            return data.map(permission => {
                                return `<span class="badge badge-light-info fs-base me-1">${permission.name}</span>`;
                            }).join('');
                        }
                    },
                    {
                        targets: 3,
                        data: 'created_at',
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function(data, type, row) {
                            return `<span>${new Intl.DateTimeFormat('vi-VN', {day: '2-digit', month: '2-digit', year: 'numeric'}).format(new Date(data))}</span>`;
                        }
                    },
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-warning btn-active-light-warning btn-sm btn-edit">Sửa</button>
                                <button class="btn btn-danger btn-active-light-danger btn-sm btn-delete">Xóa</button>
                            `;
                        },
                    },
                ],
            });

            $('#role-modal').on('hidden.bs.modal', function(e) {
                $(this).find('input[name="id"]').val('');
                $(this).find('form')[0].reset();
            })

            $('#form-role').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const id = form.find('input[name="id"]').val();
                const name = form.find('input[name="name"]').val();
                const permissions = [];
                $.each(form.find('input[name="permissions[]"]:checked'), function() {
                    permissions.push($(this).val());
                });

                $.ajax({
                    url: id ? "{{ route('api.role.update') }}" : "{{ route('api.role.store') }}",
                    type: "POST",
                    data: {
                        id: id,
                        name: name,
                        permissions: permissions,
                    },
                    beforeSend: function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", document.querySelector(
                            'meta[name="csrf-token"]').getAttribute('content'));
                    },
                    success: function(res) {
                        if (res.code == 0) {
                            notify('Lưu thành công', 'success');
                            $('#role-modal').modal('hide');
                            datatable.draw();
                        } else {
                            notify(res.data[0], 'error');
                        }
                    },
                    error: function(err) {
                        console.log(err);
                        notify(err.message, 'error');
                    }
                });
            })

            $(document).on('click', '.btn-edit', function() {
                const data = datatable.row($(this).closest('tr')).data();
                $('#form-role').find('input[name="id"]').val(data.id);
                $('#form-role').find('input[name="name"]').val(data.name);
                $.each(data.permissions, function(index, value) {
                    $('#form-role').find(`input[name="permissions[]"][value="${value.name}"]`).prop(
                        'checked', true);
                });
                $('#role-modal').modal('show');
            })

            $(document).on('click', '.btn-delete', function() {
                const id = datatable.row($(this).closest('tr')).data().id;
                notify('Bạn chắc chắn muốn xóa vai trò này?', 'warning', true).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('api.role.delete') }}",
                            type: "POST",
                            data: {
                                id: id,
                            },
                            beforeSend: function(request) {
                                request.setRequestHeader("X-CSRF-TOKEN", document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'));
                            },
                            success: function(res) {
                                if (res.code == 0) {
                                    notify('Xóa thành công', 'success');
                                    datatable.draw();
                                } else {
                                    notify(res.message, 'error');
                                }
                            },
                            error: function(err) {
                                console.log(err);
                                notify(err.message, 'error');
                            }
                        });
                    }
                })
            })


        })
    </script>
@endsection
