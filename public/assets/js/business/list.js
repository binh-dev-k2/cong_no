"use strict";

var CustomerList = function () {
    let timeoutSearch, prevId = null;

    const headers = {
        Authorization: `Bearer ${token}`,
    };

    // var updateToolbar = () => {
    //     const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
    //     const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
    //     const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
    //     const checkboxes = document.querySelectorAll(' tbody [type="checkbox"]');
    //     let anyChecked = false;
    //     let checkedCount = 0;

    //     checkboxes.forEach((checkbox => {
    //         if (checkbox.checked) {
    //             anyChecked = true;
    //             checkedCount++;
    //         }
    //     }));

    //     if (anyChecked) {
    //         selectedCount.innerHTML = checkedCount;
    //         baseToolbar.classList.add("d-none");
    //         selectedToolbar.classList.remove("d-none");
    //     } else {
    //         baseToolbar.classList.remove("d-none");
    //         selectedToolbar.classList.add("d-none");
    //     }

    //     return checkedCount;
    // };

    const handleSearchDatatable = () => {
        $('#business_search').on("keyup", (function (e) {
            clearTimeout(timeoutSearch)
            timeoutSearch = setTimeout(function () {
                prevId = null
                datatable.draw();
            }, 500)
        }));
    }

    const formatDate = (time) => {
        const dateTime = new Date(time);
        const year = dateTime.getFullYear();
        const month = String(dateTime.getMonth() + 1).padStart(2, "0");
        const day = String(dateTime.getDate()).padStart(2, "0");
        return `${day}-${month}-${year}`;
    }

    const initEditPayExtra = () => {
        const editPayExtraBtns = document.querySelectorAll('.btn-edit-pay-extra');
        editPayExtraBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();

                const td = btn.closest('td');
                td.querySelector('.container-pay-extra').classList.add('d-none');
                td.innerHTML += `
                                    <div class="d-flex container-edit-pay-extra">
                                        <input type="number" value="${data.pay_extra ?? 0}" class="form-control" style="min-width: 150px; max-width:200px" min="0"/>
                                        <button class="btn btn-light btn-close-pay-extra px-3 py-2">Đóng</button>
                                        <button class="btn btn-success btn-save-pay-extra px-3 py-2">Lưu</button>
                                    </div>
                                `

                td.querySelector('.btn-close-pay-extra').addEventListener('click', (e) => {
                    datatable.draw();
                })

                td.querySelector('.btn-save-pay-extra').addEventListener('click', (e) => {
                    const value = td.querySelector('.container-edit-pay-extra input').value;

                    axios.post(routes.businessUpdatePayExtra, { id: data.id, pay_extra: value }, { headers: headers })
                        .then((res) => {
                            if (res.data.code == 0) {
                                notify('Lưu thành công!', 'success').then(() => { datatable.draw(); })
                            } else {
                                notify(res.data.data.join(", "), 'error')
                            }
                        })
                })
            })
        })
    }

    const initViewMoney = () => {
        const viewMoneyBtns = document.querySelectorAll('.btn-view-money');
        viewMoneyBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                const id = data.id

                axios.post(routes.businessViewMoney, { id: id }, { headers: headers })
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

    const initComplete = () => {
        const completeBtns = document.querySelectorAll('.btn-complete');
        completeBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();

                notify('Hoàn thành thẻ này?', 'warning', true).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(routes.businessComplete, { id: data.id }, { headers: headers })
                            .then((res) => {
                                if (res.data.code == 0) {
                                    notify('Hoàn thành!', 'success').then(() => { datatable.draw(); })
                                } else {
                                    notify(res.data.data.join(", "), 'error')
                                }
                            })
                            .catch((err) => {
                                console.log(err);
                                notify(err.message, 'error')
                            })
                    }
                })

            })
        })
    }

    const caculateFee = (totalMoney, feePercent) => {
        return parseFloat(parseFloat(feePercent / 100) * totalMoney).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1)
    }

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

    return {
        initDatatable: async function () {
            datatable = $("#business_table").DataTable({
                fixedColumns: {
                    leftColumns: 1,
                    // rightColumns: 1
                },
                processing: true,
                serverSide: true,
                // order: [
                //     [2, 'desc']
                // ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    className: 'row-selected'
                },
                ajax: {
                    url: routes.datatable,
                    type: "POST",
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", `Bearer ${token}`);
                    },
                    data: function (d) {
                        d.search = $('#business_search').val();
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        data: 'id',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="${data}" />
                                    </div>`;
                        }
                    },
                    {
                        targets: 1,
                        data: 'created_at',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${formatDate(data)}</span>`
                        }
                    },
                    {
                        targets: 2,
                        data: 'customer',
                        orderable: false,
                        render: function (data, type, row) {
                            if (type === 'display' && data.id != prevId) {
                                prevId = data.id
                                return `<span>${data.name} - ${data.phone}</span>`
                            }
                            return `<span></span>`
                        }
                    },
                    {
                        targets: 3,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex flex-column align-items-center">
                                        <img src="https://api.vietqr.io/img/${row.bank_code}.png" class="h-30px" alt="${row.bank_code}">
                                        ${row.card_number}
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 4,
                        data: 'fee_percent',
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<span>${data}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        data: 'total_money',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1)}</span>`;
                        }
                    },
                    {
                        targets: 6,
                        data: 'formality',
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<span>${data}</span>`;
                        }
                    },
                    {
                        targets: 7,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${caculateFee(row.total_money, row.fee_percent)}</span>`;
                        }
                    },
                    {
                        targets: 8,
                        data: 'pay_extra',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex align-items-center justify-content-between container-pay-extra">
                                        <span class="me-2">${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <button class="btn btn-warning btn-edit-pay-extra">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `
                                    <button class="btn btn-light btn-light-primary btn-sm btn-view-money">Xem số tiền</button>
                                    <button class="btn btn-light btn-light-success btn-sm btn-complete">Hoàn thành</button>
                                    `;
                        },
                    },
                ],
            });

            // Re-init functions
            datatable.on('draw', function () {
                initComplete()
                initViewMoney()
                initEditPayExtra()
                KTMenu.createInstances()
            })
            handleSearchDatatable()

        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    CustomerList.initDatatable();
}));
