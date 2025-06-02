@extends('layouts.layout')
@section('title')
    Nghiệp vụ đã hoàn thành
@endsection

@section('header')
    <style>
        .hover-lift {
            transition: transform 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }
    </style>
@endsection

@section('content')
    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar py-4 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap flex-md-nowrap">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-2 flex-column justify-content-center my-0">
                    Nghiệp vụ đã hoàn thành
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Đối ứng</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Nghiệp vụ đã hoàn thành</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card shadow-sm">
                <div class="card-header bg-light-primary">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center w-100 gap-3">
                        <div class="flex-grow-1">
                            <h3 class="card-title text-primary fw-bold fs-3 mb-0">
                                <i class="bi bi-check-circle me-2"></i>
                                Nghiệp vụ đã hoàn thành
                            </h3>
                            <p class="text-gray-600 mb-0 mt-2">Danh sách tất cả nghiệp vụ đã hoàn thành từ các đại lý</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-6">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Lọc theo đại lý:</label>
                            <select class="form-select" id="agencyFilterSelect">
                                <option value="">Tất cả đại lý</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Từ ngày:</label>
                            <input type="date" class="form-control" id="dateFromFilter">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Đến ngày:</label>
                            <input type="date" class="form-control" id="dateToFilter">
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="completedBusinessesTable">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Đại lý</th>
                                    <th class="text-center">Máy</th>
                                    <th class="text-center">Tổng tiền</th>
                                    <th class="text-center">Mã chuẩn</th>
                                    <th class="text-center min-w-125px">Ảnh mặt trước</th>
                                    <th class="text-center min-w-125px">Ảnh tổng quan</th>
                                    <th class="text-center">Số tiền trả đại lý</th>
                                    <th class="text-center">Ngày hoàn thành</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTable -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        let datatable;

        const loadAgencyFilterOptions = () => {
            axios.get("{{ route('api.agency.list') }}")
                .then(response => {
                    if (response.data.code === 0) {
                        const select = $('#agencyFilterSelect');
                        select.empty().append('<option value="">Tất cả đại lý</option>');

                        response.data.data.forEach(agency => {
                            select.append(
                                `<option value="${agency.id}">${agency.name}</option>`);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading agencies for filter:', error);
                });
        }

        const bindCompletedBusinessEvents = () => {
            $('#agencyFilterSelect, #dateFromFilter, #dateToFilter').on('change',
                function() {
                    if (datatable) {
                        datatable.ajax.reload();
                    }
                });
        }

        $(document).ready(function() {
            const token = $('meta[name="csrf-token"]').attr('content');
            loadAgencyFilterOptions();
            bindCompletedBusinessEvents();

            // Khởi tạo Fancybox
            Fancybox.bind("[data-fancybox]", {
                // Tùy chọn Fancybox
                loop: true,
                buttons: [
                    "zoom",
                    "slideShow",
                    "fullScreen",
                    "close"
                ],
                animationEffect: "zoom-in-out",
                transitionEffect: "fade"
            });

            datatable = $('#completedBusinessesTable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('api.agency-business.list') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                    },
                    data: function(d) {
                        d.agency_id = $('#agencyFilterSelect').val();
                        d.date_from = $('#dateFromFilter').val();
                        d.date_to = $('#dateToFilter').val();
                    },
                    error: function(xhr, error, code) {
                        console.error('DataTable AJAX Error:', error, code, xhr.responseText);
                    },
                },
                columns: [{
                        data: null,
                        name: 'stt',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: 'text-center fw-bold'
                    },
                    {
                        data: 'agency',
                        name: 'agency.name',
                        render: function(data, type, row) {
                            return `
                                    <div class="fw-bold text-primary text-center">${data.name}</div>
                                `;
                        }
                    },
                    {
                        data: 'machine',
                        name: 'machine.name',
                        render: function(data, type, row) {
                            const machineName = data ? data.name : 'N/A';
                            return `
                                    <div class="text-center">
                                        ${machineName}
                                    </div>
                                `;
                        }
                    },
                    {
                        data: 'total_money',
                        name: 'total_money',
                        render: function(data, type, row) {
                            const formatted = new Intl.NumberFormat('vi-VN').format(data);
                            return `<span class="fw-bold text-success">${formatted}</span>`;
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'standard_code',
                        name: 'standard_code',
                        render: function(data, type, row) {
                            return data ?
                                `<code class="bg-light px-2 py-1 rounded">${data}</code>` :
                                '';
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'image_front',
                        name: 'image_front',
                        render: function(data, type, row) {
                            if (!data) return 'N/A';
                            const url = window.location.origin + '/storage/' + data;
                            return `<a href="${url}" data-fancybox="gallery" data-caption="Ảnh trước">
                                    <img src="${url}" loading="lazy" alt="Ảnh trước" class="thumbnail-image">
                                </a>`;
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'image_summary',
                        name: 'image_summary',
                        render: function(data, type, row) {
                            if (!data) return 'N/A';
                            const url = window.location.origin + '/storage/' + data;
                            return `<a href="${url}" data-fancybox="gallery" data-caption="Ảnh tổng quan">
                                    <img src="${url}" loading="lazy" alt="Ảnh tổng quan" class="thumbnail-image">
                                </a>`;
                        },
                        className: 'text-center min-w-100'
                    },
                    {
                        data: 'amount_to_pay',
                        name: 'amount_to_pay',
                        searchable: false,
                        render: function(data, type, row) {
                            const formatted = new Intl.NumberFormat('vi-VN').format(data);
                            return `
                                    <div class="fw-bold text-success text-center">
                                        ${formatted}
                                    </div>
                                `;
                        },
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        orderable: false,
                        render: function(data, type, row) {
                            return new Date(data).toLocaleDateString('vi-VN');
                        },
                        className: 'text-center'
                    }
                ],
                order: [
                    [6, 'desc']
                ], // Sort by completion date desc
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                dom: '<"row"<"col-sm-6 d-flex align-items-center justify-content-start"l><"col-sm-6 d-flex align-items-center justify-content-end"f>>' +
                    '<"table-responsive"t>' +
                    '<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"i><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"p>>',
                drawCallback: function(settings) {
                    // Custom styling after table draw
                    $('#completedBusinessesTable_wrapper .dataTables_filter input').addClass(
                        'form-control');
                    $('#completedBusinessesTable_wrapper .dataTables_length select').addClass(
                        'form-select');
                }
            });
        });
    </script>
@endsection
