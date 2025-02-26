@extends('layouts.layout')
@section('title')
    Lịch sử
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
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_toolbar_container'}"
                class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">

                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Lịch sử
                </h1>

                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Thống kê</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Lịch sử</li>
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
                            <input type="text" id="user_search" class="form-control form-control-solid w-250px ps-12"
                                placeholder="Tìm kiếm" />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-reponsive align-middle table-row-dashed table-bordered fs-6 gy-5"
                        id="activity-log-table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-center min-w-125px">STT</th>
                                <th class="text-center min-w-125px">Người thực hiện</th>
                                <th class="text-center min-w-125px">Thay đổi</th>
                                <th class="text-center min-w-125px">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const formatTime = (time) => {
                const dateTime = new Date(time);

                const year = dateTime.getFullYear();
                const month = String(dateTime.getMonth() + 1).padStart(2, "0");
                const day = String(dateTime.getDate()).padStart(2, "0");
                const hours = String(dateTime.getHours()).padStart(2, "0");
                const minutes = String(dateTime.getMinutes()).padStart(2, "0");

                return `${day}/${month}/${year} ${hours}:${minutes}`;
            }

            const formatKeyAttributes = (attributes) => {
                return Object.fromEntries(
                    Object.entries(attributes)
                        .filter(([key]) => !['created_at', 'updated_at'].includes(key))
                        .map(([key, value]) => {
                            const newKey = {
                                'id': 'ID',
                                'name': 'Tên',
                                'code': 'Mã',
                                'fee_percent': '% phí',
                                'account_name': 'Tên tài khoản',
                                'phone': 'Số diện thoại',
                                'card_number': 'Sô thẻ',
                                'total_money': 'Tổng số tiền',
                                'formality': 'Hình thức',
                                'fee': 'Phí',
                                'pay_extra': 'Tiền trả thêm',
                                'bank_code': 'Ngân hàng',
                                'money': 'Số tiền',
                                'note': 'Ghi chú',
                                'key': 'Key',
                                'value': 'Giá trị',
                                'type': 'Loại',
                                'customer_id': 'ID khách hàng',
                                'account_number': 'Số tài khoản',
                                'login_info': 'Thông tin đăng nhập',
                                'date_due': 'Ngày đến hạn',
                                'date_return': 'Ngày trả',
                                'status': 'Trạng thái',
                                'month_expired': 'Tháng hết hạn',
                                'year_expired': 'Năm hết hạn',
                                'total_amount': 'Tổng số tiền',
                                'business_id': 'ID nghiệp vụ',
                                'machine_id': 'ID máy',
                                'is_money_checked': 'Đánh dấu số tiền',
                                'is_note_checked': 'Đánh dấu ghi chú'
                            }[key] || key;

                            return [newKey, value];
                        })
                );
            }

            const datatable = $("#activity-log-table").DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('api.activity-log.list') }}",
                    type: "POST",
                    beforeSend: function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", document.querySelector(
                            'meta[name="csrf-token"]').getAttribute('content'));
                    },
                    data: function(d) {
                        // d.search = $('#role-search').val();
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
                        data: 'user',
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<span>${data?.name ?? ''}</span>`
                        }
                    },
                    {
                        targets: 2,
                        data: 'log',
                        orderable: false,
                        className: 'min-w-150px',
                        render: function(data, type, row) {
                            data = JSON.parse(data);

                            const event = data.event == 'created' ? 'Tạo mới' : (data.event ==
                                'updated' ? 'Cập nhật' : 'Xóa');
                            const attributes = formatKeyAttributes(data.attributes);
                            const changes = formatKeyAttributes(data.changes);

                            return `
                                    <div>
                                        <span>Sự kiện: ${event}</span>
                                        <ul>
                                            ${Object.entries(attributes).map(([key, value], index) => {
                                                return `
                                                            <li>
                                                                <span>${key}:</span>
                                                                <span>${value ?? ''}</span>
                                                            </li>
                                                            `
                                            }).join('')}
                                        </ul>
                                        ${Object.entries(changes).length > 0 ? `
                                                <div>Thay đổi:</div>
                                                <ul>
                                                    ${Object.entries(changes).map(([key, value], index) => {
                                                        return `
                                                            <li>
                                                                <span>${key}:</span>
                                                                <span>${value}</span>
                                                            </li>
                                                            `
                                                    }).join('')}
                                                </ul>
                                            ` : ''}
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 3,
                        data: 'created_at',
                        orderable: false,
                        className: 'min-w-150px',
                        render: function(data, type, row) {
                            return `<span>${formatTime(data)}</span>`;
                        }
                    },
                ],
            });
        })
    </script>
@endsection
