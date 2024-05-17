"use strict";

var CustomerList = function () {
    var timeoutSearch, prevPhone = null;

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
                prevPhone = null
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
                                notify('Lưu thành công!', 'success').then(() => {
                                    prevPhone = null;
                                    datatable.draw();
                                })
                            } else {
                                notify(res.data.data.join(", "), 'error')
                            }
                        })
                })
            })
        })
    }

    const initEditBusinessMoney = () => {
        const editBusinessMoneyBtns = document.querySelectorAll('.btn-edit-business-money');
        editBusinessMoneyBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                const dataId = btn.getAttribute('data-id');
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                const businessMoney = data.money[dataId]

                console.log(businessMoney);
                const td = btn.closest('td');
                td.querySelector('.container-business-money').classList.add('d-none');
                td.innerHTML += `
                                    <div class="d-flex align-items-center container-edit-business-money">
                                        <input type="number" data-type="money" value="${businessMoney.money}" class="form-control me-2" style="min-width: 150px; max-width:200px" min="0" step="any"/>
                                        <input type="checkbox" data-type="is_money_checked" class="form-check-input me-2" ${businessMoney.is_money_checked ? "checked" : ""}/>
                                        <input type="text" data-type="note" value="${businessMoney.note ?? ''}" class="form-control me-2" style="min-width: 150px; max-width:200px"/>
                                        <input type="checkbox" data-type="is_note_checked" class="form-check-input me-2" ${businessMoney.is_note_checked ? "checked" : ""}/>
                                        <button class="btn btn-light btn-close-business-money px-3 py-2">Đóng</button>
                                        <button class="btn btn-success btn-save-business-money px-3 py-2">Lưu</button>
                                    </div>
                                `

                td.querySelector('.btn-close-business-money').addEventListener('click', (e) => {
                    datatable.draw();
                })

                td.querySelector('.btn-save-business-money').addEventListener('click', (e) => {
                    const money = td.querySelector('.container-edit-business-money input[data-type="money"]').value;
                    const isMoneyChecked = td.querySelector('.container-edit-business-money input[data-type="is_money_checked"]').checked;
                    const note = td.querySelector('.container-edit-business-money input[data-type="note"]').value;
                    const isNoteChecked = td.querySelector('.container-edit-business-money input[data-type="is_note_checked"]').checked;

                    const body = {
                        id: businessMoney.id,
                        money: money,
                        is_money_checked: isMoneyChecked,
                        note: note,
                        is_note_checked: isNoteChecked
                    }

                    axios.post(routes.businessUpdateBusinessMoney, body, { headers: headers })
                        .then((res) => {
                            if (res.data.code == 0) {
                                notify('Lưu thành công!', 'success').then(() => {
                                    prevPhone = null;
                                    datatable.draw();
                                })
                            } else {
                                notify(res.data.data.join(", "), 'error')
                            }
                        })
                })
            })
        })
    }

    // const initViewMoney = () => {
    //     const viewMoneyBtns = document.querySelectorAll('.btn-view-money');
    //     viewMoneyBtns.forEach((btn) => {
    //         btn.addEventListener('click', () => {
    //             const row = btn.closest('tr');
    //             const data = datatable.row(row).data();
    //             const id = data.id

    //             axios.post(routes.businessViewMoney, { id: id }, { headers: headers })
    //                 .then((res) => {
    //                     if (res.status === 200) {
    //                         $('#money-modal .modal-dialog').html(res.data);
    //                         $('#money-modal').modal('show');
    //                     } else {
    //                         notify("Có lỗi xảy ra...", 'error')
    //                     }
    //                 })
    //                 .catch((err) => {
    //                     console.log(err);
    //                     notify(err.message, 'error')
    //                 })
    //         })
    //     })
    // }

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
                    leftColumns: 0,
                    rightColumns: 1
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
                    // {
                    //     targets: 0,
                    //     data: 'id',
                    //     orderable: false,
                    //     render: function (data, type, row) {
                    //         return `
                    //                 <div class="form-check form-check-sm form-check-custom form-check-solid">
                    //                     <input class="form-check-input" type="checkbox" value="${data}" />
                    //                 </div>`;
                    //     }
                    // },
                    {
                        targets: 0,
                        data: 'created_at',
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<span>${formatDate(data)}</span>`
                        }
                    },
                    {
                        targets: 1,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            if (type === 'display' && row.phone != prevPhone) {
                                prevPhone = row.phone
                                return `<span>${row.name} - ${row.phone}</span>`
                            }
                            return `<span></span>`
                        }
                    },
                    {
                        targets: 2,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex flex-column align-items-center">
                                        <img src="${row.bank.logo}" class="h-30px" alt="${row.bank.code}">
                                        ${row.card_number}
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 3,
                        data: 'fee_percent',
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<span>${data}</span>`;
                        }
                    },
                    {
                        targets: 4,
                        data: 'total_money',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        data: 'formality',
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<span>${data}</span>`;
                        }
                    },
                    {
                        targets: 6,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${row.fee.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1)}</span>`;
                        }
                    },
                    {
                        targets: 7,
                        data: 'money.0',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${0}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 8,
                        data: 'money.1',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${1}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 9,
                        data: 'money.2',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${2}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 10,
                        data: 'money.3',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${3}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 11,
                        data: 'money.4',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${4}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 12,
                        data: 'money.5',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${5}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 13,
                        data: 'money.6',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${6}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 14,
                        data: 'money.7',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${7}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 15,
                        data: 'money.8',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${8}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 16,
                        data: 'money.9',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <span class="me-2 max-h-30px ${data.is_money_checked ? 'bg-info text-white p-2 rounded' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        <span class="me-2"> - </span>
                                        <span class="me-2 min-w-50px ${data.is_note_checked ? 'bg-info text-white p-2 rounded text-truncate' : ''}" style="min-height:34px; max-width: 125px">${data?.note ?? ''}</span>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${9}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 17,
                        data: 'pay_extra',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex align-items-center justify-content-between container-pay-extra">
                                        <span class="me-2">${data?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                        -
                                        <button class="btn btn-warning btn-edit-pay-extra p-2">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function (data, type, row) {
                            return `
                                    <button class="btn btn-light btn-light-success btn-sm btn-complete">Hoàn thành</button>
                                    `;
                        },
                    },
                ],
            });

            // Re-init functions
            datatable.on('draw', function () {
                initComplete()
                // initViewMoney()
                initEditBusinessMoney()
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
