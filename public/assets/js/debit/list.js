"use strict";

var DebitsList = function () {
    let timeoutSearch;
    const headers = {
        Authorization: `Bearer ${token}`,
    };
    const handleSearchDatatable = () => {
        document.querySelector('#debit_search')
            .addEventListener("keyup", (function (e) {
                clearTimeout(timeoutSearch)
                timeoutSearch = setTimeout(function () {
                    datatable.draw();
                }, 300)
            }));
    }

    const doneDebit = () => {
        let btnDones = document.querySelectorAll('.btn-done');
        btnDones.forEach((btnDone) => {
            const row = btnDone.closest('tr');
            const data = datatable.row(row).data();
            const dataId = { id: data.id }
            btnDone.addEventListener('click', function () {
                Swal.fire({
                    text: "Bạn có chắc chắn muốn hoàn thành không?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Có",
                    cancelButtonText: "Không",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        axios.post(routes.updateDebitStatus, dataId, { headers: headers })
                            .then((response) => {
                                Swal.fire({
                                    text: "Cập nhật trạng thái thành công",
                                    icon: "success",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    }
                                }).then(function () {
                                    datatable.draw();
                                });
                            })

                    }
                });
            });
        });
    }

    return {
        initDatatable: async function () {
            datatable = $("#kt_debit_table").DataTable({
                fixedColumns: {
                    leftColumns: 1,
                    rightColumns: 1
                },
                searchDelay: 500,
                processing: true,
                serverSide: true,
                order: [
                    // [2, 'desc']
                ],
                stateSave: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    className: 'row-selected'
                },
                ajax: {
                    url: routes.getAllDebitCards,
                    type: "POST",
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", `Bearer ${token}`);
                    },
                    data: function (d) {
                        d.search = $('input[data-kt-debit-table-filter]').val();
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            if (type === 'display') {
                                return `<span>${data.name ?? ''} - ${data.phone ?? ''}</span>`;
                            }
                            return '<span></span>'
                        }
                    },
                    {
                        targets: 1,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex flex-column align-items-center">
                                        <img src="${row.card.bank.logo}" class="h-30px" alt="${row.card.bank.code}">
                                        ${row.card_number}
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 2,
                        data: 'formality',
                        className: 'text-center',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data}</span>`;
                        }
                    },
                    {
                        targets: 3,
                        data: 'fee',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 4,
                        data: 'pay_extra',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        data: 'total_amount',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 6,
                        data: 'status',
                        className: 'text-center',
                        orderable: false,
                        render: function (data, type, row) {
                            if (data === 0) {
                                return `<span>Chưa thu</span>`;
                            }
                            return `<span>Đã thu</span>`;
                        }
                    },
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            if (row.status === 0) {
                                return `<span class="btn btn-sm btn-primary btn-active-light-primary btn-done" data-value="${row.id}">Hoàn thành</span>`;
                            }
                            return `<span></span>`;
                        }
                    }

                ]
            });

            // Re-init functions
            datatable.on('draw', function () {
                doneDebit();
                handleSearchDatatable();
            })
        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    DebitsList.initDatatable();
}));
