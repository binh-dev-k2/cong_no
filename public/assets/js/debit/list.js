"use strict";

var DebitsList = function () {
    let timeoutSearch, prevPhone = null;
    const headers = {
        Authorization: `Bearer ${token}`,
    };
    const handleSearchDatatable = () => {
        document.querySelector('#debit_search')
            .addEventListener("keyup", (function (e) {
                clearTimeout(timeoutSearch)
                timeoutSearch = setTimeout(function () {
                    prevPhone = null;
                    datatable.draw();
                }, 300)
            }));
    }

    const handleMonthFilter = () => {
        document.querySelector('#debit_month')
            .addEventListener("change", (function (e) {
                prevPhone = null;
                datatable.draw();
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
                                    confirmButtonText: "Xác nhận!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    }
                                }).then(function () {
                                    prevPhone = null;
                                    datatable.draw();
                                });
                            })

                    }
                });
            });
        });
    }

    function formatNumber(number) {
        const str = number.toString();
        const formattedStr = str.replace(/(.{4})/g, '$1 ');
        return formattedStr.trim();
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
                // select: {
                //     style: 'multi',
                //     selector: 'td:first-child input[type="checkbox"]',
                //     className: 'row-selected'
                // },
                ajax: {
                    url: routes.getAllDebitCards,
                    type: "POST",
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", `Bearer ${token}`);
                    },
                    data: function (d) {
                        d.search = $('input[data-kt-debit-table-filter]').val();
                        d.month = $('#debit_month').val();
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            if (row.phone !== prevPhone && type === 'display') {
                                prevPhone = row.phone
                                return `<span>${row.name ?? ''} - ${row.phone ?? ''}</span>`
                            }
                            return `<span></span>`
                        }
                    },
                    {
                        targets: 1,
                        data: 'account_name',
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`
                        }
                    },
                    {
                        targets: 2,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex flex-column align-items-center">
                                        <img src="${row.card.bank.logo}" class="h-30px" alt="${row.card.bank.code}">
                                        ${formatNumber(row.card_number)}
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 3,
                        data: 'formality',
                        className: 'text-center',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data}</span>`;
                        }
                    },
                    {
                        targets: 4,
                        data: 'fee',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        data: 'pay_extra',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 6,
                        data: 'total_amount',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 7,
                        data: 'sum_amount',
                        className: 'text-center',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? ''}</span>`;
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
                $('.paginate_button a').on('click', function () {
                    prevPhone = null;
                });
            })
            handleSearchDatatable();
            handleMonthFilter();


        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    DebitsList.initDatatable();
}));
