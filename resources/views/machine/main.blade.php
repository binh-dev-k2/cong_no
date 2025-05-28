@extends('layouts.layout')
@section('title')
    Quản lý máy
@endsection
@section('header')
    <style>
        tr td {
            padding: 0.75rem !important;
            margin: 0 !important;
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
                    Quản lý máy
                </h1>

                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">
                            Thống kê
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Quản lý máy</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" id="machine-search"
                                    class="form-control form-control-solid w-250px ps-12"
                                    data-kt-debit-table-filter="search" placeholder="Tìm kiếm máy..." />
                            </div>

                            <div class="d-flex flex-wrap gap-3">
                                <div class="d-flex">
                                    <select class="form-select form-select-solid me-2 min-w-100px"
                                        id="machine-month-select">
                                        <option value="">Tháng</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <select class="form-select form-select-solid min-w-100px" id="machine-year-select">
                                        <option value="">Năm</option>
                                        @for ($i = now()->year; $i >= 2025; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <select class="form-select form-select-solid min-w-120px py-4" name="status"
                                        id="machine-status-select">
                                        <option value="0">Máy đã ẩn</option>
                                        <option value="1" selected>Máy đang hiển thị</option>
                                    </select>
                                </div>

                                <button class="btn btn-primary px-4" id="machine-filter">
                                    <i class="ki-duotone ki-filter fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Lọc
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#machine-modal">
                                <i class="ki-duotone ki-plus fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Thêm máy mới
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-reponsive align-middle table-row-dashed table-bordered fs-6 gy-5"
                        id="machine-table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-center min-w-50px">STT</th>
                                <th class="text-center min-w-125px">Tên - mã máy</th>
                                <th class="text-center min-w-50px">% VISA</th>
                                <th class="text-center min-w-50px">% MASTER</th>
                                <th class="text-center min-w-50px">% JCB</th>
                                <th class="text-center min-w-50px">% NAPAS</th>
                                <th class="text-center min-w-125px">Lợi nhuận</th>
                                <th class="text-center min-w-125px">Tổng số tiền</th>
                                <th class="text-center min-w-70px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="6" class="text-end">Tổng số tiền:</td>
                                <td id="machine-total-fee" class="text-primary"></td>
                                <td id="machine-total-money" class="text-primary"></td>
                                <td></td>
                            </tr>
                        </tfoot>
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
                                    % phí VISA:
                                </label>
                                <input type="text" class="form-control" name="visa_fee_percent" />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">
                                    % phí MASTER:
                                </label>
                                <input type="text" class="form-control" name="master_fee_percent" />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">
                                    % phí JCB:
                                </label>
                                <input type="text" class="form-control" name="jcb_fee_percent" />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">
                                    % phí NAPAS:
                                </label>
                                <input type="text" class="form-control" name="fee_percent" />
                            </div>

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">
                                    Trạng thái:
                                </label>
                                <select class="form-select form-select-solid" name="status">
                                    <option value="0">Ẩn</option>
                                    <option value="1" selected>Hiện</option>
                                </select>
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
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const formatter = new Intl.NumberFormat('en-US', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 3,
            });

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

            const calculateTotalMoney = (data = {}) => {
                $.ajax({
                    url: "{{ route('api.machine.totalMoney') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: data,
                    success: function(res) {
                        // console.log(res);

                        $('#machine-total-money').text(formatter.format(res.data.totalMoney));
                        $('#machine-total-fee').text(formatter.format(res.data.totalFee));
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
                        request.setRequestHeader("X-CSRF-TOKEN", token);
                    },
                    data: function(d) {
                        d.search = $('#machine-search').val();
                        d.month = $('#machine-month-select').val();
                        d.year = $('#machine-year-select').val();
                        d.status = $('#machine-status-select').val();

                        const data = {
                            status: d.status,
                            year: d.year,
                            month: d.month
                        }

                        calculateTotalMoney(data);
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
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<div class="d-flex flex-column align-items-center">
                                <span class="fw-bold text-primary fs-5 mb-1">${data.name ?? ''}</span>
                                <span class="text-muted fs-7 mb-2">Mã: ${data.code ?? ''}</span>
                                <span class="badge badge-light-${data.status ? 'success' : 'danger'} fs-7 fw-bold px-2 py-1">
                                    ${data.status ? 'Đang hoạt động' : 'Đã ẩn'}
                                </span>
                            </div>`
                        }
                    },
                    {
                        targets: 2,
                        data: 'visa_fee_percent',
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function(data, type, row) {
                            return `<span>${formatter.format(data)}</span>`;
                        }
                    },
                    {
                        targets: 3,
                        data: 'master_fee_percent',
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function(data, type, row) {
                            return data ? formatter.format(data) : 0;
                        }
                    },
                    {
                        targets: 4,
                        data: 'jcb_fee_percent',
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function(data, type, row) {
                            return data ? formatter.format(data) : 0;
                        }
                    },
                    {
                        targets: 5,
                        data: 'fee_percent',
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function(data, type, row) {
                            return data ? formatter.format(data) : 0;
                        }
                    },
                    {
                        targets: 6,
                        data: 'business_fees_sum_fee',
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function(data, type, row) {
                            return data ? new Intl.NumberFormat('vi-VN').format(data) : 0;
                        }
                    },
                    {
                        targets: 7,
                        data: 'business_fees_sum_total_money',
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function(data, type, row) {
                            return data ? new Intl.NumberFormat('vi-VN').format(data) : 0;
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
                                `;
                        },
                    },
                ],
            });

            $('#machine-filter').on('click', function(e) {
                e.preventDefault();
                if ($('#machine-month-select').val() && !$('#machine-year-select').val()) {
                    notify('Vui lòng chọn năm.', 'error');
                    return;
                }
                datatable.ajax.reload();
            })

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
                const visa_fee_percent = form.find('input[name="visa_fee_percent"]').val().replace(/[,]/g, '.');
                const master_fee_percent = form.find('input[name="master_fee_percent"]').val().replace(/[,]/g, '.');
                const jcb_fee_percent = form.find('input[name="jcb_fee_percent"]').val().replace(/[,]/g, '.');
                const fee_percent = form.find('input[name="fee_percent"]').val().replace(/[,]/g, '.');
                const status = form.find('select[name="status"]').val();

                $.ajax({
                    url: id ? "{{ route('api.machine.update') }}" :
                        "{{ route('api.machine.store') }}",
                    type: "POST",
                    data: {
                        id: id,
                        name: name,
                        code: code,
                        visa_fee_percent: parseFloat(visa_fee_percent) || null,
                        master_fee_percent: parseFloat(master_fee_percent) || null,
                        jcb_fee_percent: parseFloat(jcb_fee_percent) || null,
                        fee_percent: parseFloat(fee_percent) || null,
                        status: status
                    },
                    beforeSend: function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", document.querySelector(
                            'meta[name="csrf-token"]').getAttribute('content'));
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
                $('#form-machine').find('input[name="visa_fee_percent"]').val(data.visa_fee_percent);
                $('#form-machine').find('input[name="master_fee_percent"]').val(data.master_fee_percent);
                $('#form-machine').find('input[name="jcb_fee_percent"]').val(data.jcb_fee_percent);
                $('#form-machine').find('input[name="fee_percent"]').val(data.fee_percent);
                $('#form-machine').find('select[name="status"]').val(data.status);
                $('#machine-modal').modal('show');
            })
        })
    </script>
@endsection
