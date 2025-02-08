@extends('layouts.layout')
@section('title')
    Máy
@endsection
@section('header')
    <style>
        tr td {
            padding: 0.5rem !important;
            margin: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_toolbar_container'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">

                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Máy
                </h1>

                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Máy</li>
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
                            <input type="text" id="machine-search" class="form-control form-control-solid w-250px ps-12 "
                                data-kt-debit-table-filter="search" placeholder="Tìm kiếm" />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#machine-modal">Thêm máy</button>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-reponsive align-middle table-row-dashed table-bordered fs-6 gy-5"
                        id="machine-table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-center min-w-50px">STT</th>
                                <th class="text-center min-w-125px">Tên máy</th>
                                <th class="text-center min-w-125px">Mã máy</th>
                                <th class="text-center min-w-125px">% phí</th>
                                <th class="text-center min-w-70px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="machine-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form action="" id="form-machine">
                    <div class="modal-header">
                        <h4 class="modal-title">Thông tin máy</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-10 px-lg-17">
                        <div class="scroll-y me-n7 pe-7" style="max-height: calc(100vh - 30rem)">
                            <input type="hidden" name="id" />
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">
                                    Tên máy:
                                </label>
                                <input type="text" class="form-control" name="name" />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">
                                    Mã máy:
                                </label>
                                <input type="text" class="form-control" name="code" />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">
                                    % phí:
                                </label>
                                <input type="text" class="form-control" name="fee_percent" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex-center">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Xác nhận</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
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

            const datatable = $("#machine-table").DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('api.machine.list') }}",
                    type: "POST",
                    beforeSend: function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    },
                    data: function(d) {
                        d.search = $('#machine-search').val();
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
                        data: 'code',
                        orderable: false,
                        className: 'min-w-150px',
                        render: function(data, type, row) {
                            return `<span>${data ?? ''}</span>`
                        }
                    },
                    {
                        targets: 3,
                        data: 'fee_percent',
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function(data, type, row) {
                            return `<span>${new Intl.NumberFormat('vi-VN').format(data)}</span>`;
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

            $('#machine-modal').on('hidden.bs.modal', function(e) {
                $(this).find('input[name="id"]').val('');
                $(this).find('form')[0].reset();
            })

            $('#form-machine').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const id = form.find('input[name="id"]').val();
                const name = form.find('input[name="name"]').val();
                const code = form.find('input[name="code"]').val();
                const fee_percent = form.find('input[name="fee_percent"]').val().replace(/[,]/g, '.');

                $.ajax({
                    url: id ? "{{ route('api.machine.update') }}" :
                        "{{ route('api.machine.store') }}",
                    type: "POST",
                    data: {
                        id: id,
                        name: name,
                        code: code,
                        fee_percent: parseFloat(fee_percent),
                    },
                    beforeSend: function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    },
                    success: function(res) {
                        if (res.code == 0) {
                            notify('Lưu thành công', 'success');
                            $('#machine-modal').modal('hide');
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
                $('#form-machine').find('input[name="id"]').val(data.id);
                $('#form-machine').find('input[name="name"]').val(data.name);
                $('#form-machine').find('input[name="code"]').val(data.code);
                $('#form-machine').find('input[name="fee_percent"]').val(data.fee_percent);
                $('#machine-modal').modal('show');
            })

            $(document).on('click', '.btn-delete', function() {
                const id = datatable.row($(this).closest('tr')).data().id;
                notify('Bạn chắc chắn muốn xóa máy này?', 'warning', true).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('api.machine.delete') }}",
                            type: "POST",
                            data: {
                                id: id,
                            },
                            beforeSend: function(request) {
                              request.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
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
