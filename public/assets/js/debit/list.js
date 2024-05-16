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
            const dataId = {id : data.id}
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
                       axios.post(routes.updateDebitStatus, dataId,{ headers: headers})
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

                // scrollCollapse: true,
                // scrollX: true,
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
                        data: 'name',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 1,
                        data: 'phone',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 2,
                        data: 'card_number',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex flex-column align-items-center">
                                        <img src="https://api.vietqr.io/img/${row.card.bank_code}.png" class="h-30px" alt="${row.card.bank_code}">
                                        ${data ?? ''}
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 3,
                        data: 'formality',
                        orderable: false,
                        render: function (data, type, row) {
                            if (data === 'D'){
                                return `<span>Đáo</span>`;
                            }
                            return `<span>Rút</span>`;
                        }
                    },
                    {
                        targets: 4,
                        data: 'fee',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        data: 'total_amount',
                        orderable: false,
                        render: function (data, type, row) {
                           return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 6,
                        data: 'pay_extra',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 7,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            const totalAmount = row.total_amount !== undefined ? row.total_amount : '';
                            const payExtra = row.pay_extra !== undefined ? row.pay_extra : '';
                            return `<span>${(totalAmount + payExtra) ?? ''}</span>`;
                        }
                    },

                    {
                        targets: 8,
                        data: 'status',
                        orderable: false,
                        render: function (data, type, row) {
                            if (data===0){
                                return `<span>Chưa thu</span>`;
                            }
                            return `<span>Đã thu</span>`;
                        }
                    },
                    {
                        targets: 9,
                        data: 'status',
                        orderable: false,
                        render: function (data, type, row) {
                            if (data===0){
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
