"use strict";

var DebitsList = function () {
    let timeoutSearch, prevPhone = null;
    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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

        document.querySelector('#debit_year')
            .addEventListener("change", (function (e) {
                prevPhone = null;
                datatable.draw();
            }));
    }

    const initTotalMoney = () => {
        const totalFee = document.querySelector('.c-total-fee');
        const totalMoney = document.querySelector('.c-total-money');

        axios.post(routes.debitTotalMoney,
            {
                month: document.querySelector('#debit_month').value,
                year: document.querySelector('#debit_year').value
            },
            {
                headers: headers
            })
            .then((response) => {
                totalFee.querySelector('span').innerText = parseInt(response.data.data.totalFee).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1);
                totalMoney.querySelector('span').innerText = parseInt(response.data.data.totalMoney).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1);
            })
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

    const initViewMoney = () => {
        const viewMoneyBtns = document.querySelectorAll('.btn-view-money');
        viewMoneyBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                const business_id = data.business_id

                axios.post(routes.debitViewMoney, { business_id: business_id }, { headers: headers })
                    .then((res) => {
                        if (res.status === 200) {
                            $('#money-modal .modal-dialog').html(res.data);
                            $('#money-modal').modal('show');
                        } else {
                            notify("Có lỗi xảy ra...", 'error')
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                        notify(err.message, 'error')
                    })
            })
        })
    }

    function formatNumber(number) {
        const str = number.toString();
        const formattedStr = str.replace(/(.{4})/g, '$1 ');
        return formattedStr.trim();
    }

    const formatDate = (time) => {
        const dateTime = new Date(time);
        const year = dateTime.getFullYear();
        const month = String(dateTime.getMonth() + 1).padStart(2, "0");
        const day = String(dateTime.getDate()).padStart(2, "0");
        return `${day}-${month}-${year}`;
    }

    return {
        initDatatable: async function () {
            datatable = $("#kt_debit_table").DataTable({
                // fixedColumns: {
                //     leftColumns: 1,
                //     rightColumns: 1
                // },
                lengthMenu: [10, 20, 50, 100],
                pageLength: 50,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                ordering: false,
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
                        request.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    },
                    data: function (d) {
                        d.search = $('input[data-kt-debit-table-filter]').val();
                        d.month = $('#debit_month').val();
                        d.year = $('#debit_year').val();
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
                                const phone = row.phone.startsWith('@') ? row.phone.substring(1) : row.phone;
                                const url = row.phone.startsWith('@') ? `https://t.me/${phone}` : `https://zalo.me/${phone}`;
                                return `
                                        <div>${row.name ?? ''}</div>
                                        <a href="${url}" target="_blank">${phone}</a>
                                    `
                            }
                            return `<span></span>`
                        }
                    },
                    {
                        targets: 1,
                        data: 'updated_at',
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<span>${formatDate(data)}</span>`
                        }
                    },
                    {
                        targets: 2,
                        data: 'account_name',
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`
                        }
                    },
                    {
                        targets: 3,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex flex-column align-items-center">
                                    ${row.card ? `<img src="${row?.card.bank.logo}" class="h-30px" alt="${row?.card.bank.code}">` : '<div class="text-center text-danger">Không có ngân hàng</div>'}
                                        ${formatNumber(row.card_number)}
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 4,
                        data: 'formality',
                        className: 'text-center',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        data: 'total_money',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 6,
                        data: 'fee',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 7,
                        data: 'pay_extra',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 8,
                        data: 'total_amount',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 9,
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${row.sum_amount ? row.sum_amount.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) : ''}</span>`;
                        }
                    },
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            let actionBtns = '';
                            if (row.business_id) {
                                actionBtns += `<span class="btn btn-sm btn-primary btn-active-light-primary btn-view-money">Tiền chia</span>`;
                            }
                            if (row.status === 0) {
                                actionBtns += `<span class="btn btn-sm btn-primary btn-active-light-primary btn-done ms-2" data-value="${row.id}">Hoàn thành</span>`;
                            }
                            return `<div class="d-flex justify-content-center">${actionBtns}</div>`;
                        }
                    }
                ]
            });

            // Re-init functions
            datatable.on('draw', function () {
                doneDebit();
                initTotalMoney();
                $('.paginate_button a').on('click', function () {
                    prevPhone = null;
                });
                initViewMoney();
            })
            handleSearchDatatable();
            handleMonthFilter();


        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    DebitsList.initDatatable();
}));
