"use strict";

var BusinessList = function () {
    var timeoutSearch, timeoutNote, prevPhone = null;

    const headers = {
        Authorization: token,
    };

    const handleUpdateNote = () => {
        document.querySelector('[name="business_note"]').addEventListener('keyup', (e) => {
            clearTimeout(timeoutNote)
            timeoutNote = setTimeout(function () {
                const value = document.querySelector('[name="business_note"]').value;
                axios.post(routes.businessUpdateNote, { business_note: value }, { headers: headers })
                    .then((res) => {
                    })
                    .catch((err) => {
                        console.log(err);

                    })
            }, 500)
        })
    }

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
        const hour = String(dateTime.getHours()).padStart(2, "0");
        const minute = String(dateTime.getMinutes()).padStart(2, "0");
        return `${day}-${month}-${year} ${hour}:${minute}`;
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

                const td = btn.closest('td');
                td.querySelector('.container-business-money').classList.add('d-none');
                td.innerHTML += `
                                    <div class="d-flex align-items-center container-edit-business-money">
                                        <input type="text" data-type="money" value="${businessMoney.money}" class="form-control me-2" style="min-width: 150px; max-width:200px" min="0" step="any"/>
                                        <input type="checkbox" data-type="is_money_checked" class="form-check-input me-2" ${businessMoney.is_money_checked ? "checked" : ""}/>
                                        <input type="text" data-type="note" value="${businessMoney.note ?? ''}" class="form-control me-2" style="min-width: 150px; max-width:200px"/>
                                        <input type="checkbox" data-type="is_note_checked" class="form-check-input me-2" ${businessMoney.is_note_checked ? "checked" : ""}/>
                                        <button class="btn btn-light btn-close-business-money px-3 py-2">Đóng</button>
                                        <button class="btn btn-success btn-save-business-money px-3 py-2">Lưu</button>
                                    </div>
                                `

                td.querySelector('.btn-close-business-money').addEventListener('click', (e) => {
                    prevPhone = null;
                    datatable.draw();
                })

                td.querySelector('.btn-save-business-money').addEventListener('click', (e) => {
                    const money = td.querySelector('.container-edit-business-money input[data-type="money"]').value.replace(/,/g, '');
                    const isMoneyChecked = td.querySelector('.container-edit-business-money input[data-type="is_money_checked"]').checked;
                    const note = td.querySelector('.container-edit-business-money input[data-type="note"]').value;
                    const isNoteChecked = td.querySelector('.container-edit-business-money input[data-type="is_note_checked"]').checked;

                    const body = {
                        id: businessMoney.id,
                        money: parseInt(money),
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

    const formatter = new Intl.NumberFormat('en-US', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

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
                                    notify('Hoàn thành!', 'success').then(() => {
                                        prevPhone = null
                                        datatable.draw();
                                    })
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

    const initDelete = () => {
        const deleteBtns = document.querySelectorAll('.btn-delete');
        deleteBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                const id = data.id

                notify('Bạn có chắc muốn xóa nghiệp vụ này?', 'warning', true).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(routes.businessDelete, { id: id }, { headers: headers })
                            .then((res) => {
                                if (res.data.code == 0) {
                                    prevPhone = null
                                    datatable.draw();
                                } else {
                                    notify('Có lỗi xảy ra, vui lòng thử lại sau!', 'error')
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

    const initAction = () => {
        const actionBtns = document.querySelectorAll('[data-kt-menu-trigger="click"]');
        actionBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                actionBtns.forEach((btn) => {
                    btn.closest('td').style.zIndex = 0
                })
                const row = btn.closest('td');
                row.style.zIndex = 99
            })
        })
    }

    const initEdit = () => {
        const editBtns = document.querySelectorAll('.btn-edit');
        editBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();

                const modalEdit = document.querySelector('#modal-edit');
                modalEdit.querySelector('input[name="id"]').value = data.id;
                modalEdit.querySelector('input[name="card_number"]').value = data.card_number;
                modalEdit.querySelector('input[name="account_name"]').value = data.account_name;
                modalEdit.querySelector('input[name="name"]').value = data.name;
                modalEdit.querySelector('input[name="phone"]').value = data.phone;
                modalEdit.querySelector('input[name="fee_percent"]').value = data.fee_percent;
                data.formality ? modalEdit.querySelector('input[name="formality"][value="' + data.formality + '"]').checked = true : '';
                modalEdit.querySelector('input[name="total_money"]').value = data.total_money;
                modalEdit.querySelector('select[name="machine_id"]').value = data.machine_id;
            })
        })
    }

    const initEditSetting = () => {
        const editSettingBtn = document.querySelector('.btn-edit-setting');

        editSettingBtn.addEventListener('click', () => {
            axios.get(routes.businessEditSetting, { headers: headers })
                .then((res) => {
                    if (res.status === 200) {
                        $('#modal-edit-setting .modal-dialog').html(res.data);
                        $('#modal-edit-setting').modal('show');
                    } else {
                        notify("Có lỗi xảy ra...", 'error')
                    }
                })
                .catch((err) => {
                    console.log(err);
                    notify(err.message, 'error')
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

    function formatNumber(number) {
        // Chuyển số sang chuỗi nếu nó chưa phải là chuỗi
        const str = number.toString();

        // Sử dụng biểu thức chính quy để chia thành từng nhóm 4 chữ số
        const formattedStr = str.replace(/(.{4})/g, '$1 ');

        // Cắt bỏ khoảng trắng cuối cùng nếu có
        return formattedStr.trim();
    }

    $(document).on('input', 'input[data-type="money"]', function () {
        const value = $(this).val().replace(/[^0-9.]/g, '');
        if (value === '') {
            $(this).val('');
        } else {
            $(this).val(formatter.format(parseInt(value)));
        }
    })

    return {
        initDatatable: async function () {
            datatable = $("#business_table").DataTable({
                // fixedColumns: {
                //     leftColumns: 0,
                //     rightColumns: 1
                // },
                lengthMenu: [10, 20, 50, 100],
                pageLength: 50,
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: routes.datatable,
                    type: "POST",
                    beforeSend: function (request) {
                        request.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
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
                        className: 'text-center min-w-150px',
                        render: function (data, type, row) {
                            if (type === 'display') {
                                if (row.account_name) {
                                    if (row.phone) {
                                        const phone = row.phone.startsWith('@') ? row.phone.substring(1) : row.phone;
                                        const url = row.phone.startsWith('@') ? `https://t.me/${phone}` : `https://zalo.me/${phone}`;
                                        return `
                                                <div>${row.account_name}</div>
                                                <a href="${url}" target="_blank">${phone}</a>
                                            `
                                    }
                                    return `<div>${row.account_name}</div>`
                                }

                                if (row.phone != prevPhone) {
                                    prevPhone = row.phone
                                    const phone = row.phone.startsWith('@') ? row.phone.substring(1) : row.phone;
                                    const url = row.phone.startsWith('@') ? `https://t.me/${phone}` : `https://zalo.me/${phone}`;

                                    return `
                                        <div>${row.name}</div>
                                        <a href="${url}" target="_blank">${phone}</a>
                                    `
                                }
                            }
                            return `<div></div>`
                        }
                    },
                    {
                        targets: 2,
                        data: null,
                        orderable: false,
                        className: 'text-center min-w-175px',
                        render: function (data, type, row) {
                            return `<div class="d-flex flex-column align-items-center">
                                        <img src="${row.bank.logo}" loading="lazy" class="h-30px" alt="${row.bank.code}">
                                        <span>${formatNumber(row.card_number)}</span>
                                        ${formatNumber(row.card.account_number ? 'STK: ' + row.card.account_number : '')}
                                        ${row.card.date_due ? `<span class="text-primary">Ngày đến hạn: ${row.card.date_due}</span>` : ''}
                                        ${row.machine ? `<span class="text-primary">Máy: ${row.machine.name}</span>` : ''}
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
                            return `<span>${row?.fee.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>`;
                        }
                    },
                    {
                        targets: 7,
                        data: 'money.0',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                    <div class="d-flex align-items-center justify-content-between container-business-money">
                                        <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                                            <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}" style="display: flex; align-items: center; justify-content: center;">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                            <span class="me-2"> - </span>
                                            <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px; display: flex; align-items: center; justify-content: center;">${data?.note ?? ''}</span>
                                        </div>
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
                                        <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                                            <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}" style="display: flex; align-items: center; justify-content: center;">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                            <span class="me-2"> - </span>
                                            <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px; display: flex; align-items: center; justify-content: center;">${data?.note ?? ''}</span>
                                        </div>
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
                                        <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                                            <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}" style="display: flex; align-items: center; justify-content: center;">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                            <span class="me-2"> - </span>
                                            <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px; display: flex; align-items: center; justify-content: center;">${data?.note ?? ''}</span>
                                        </div>
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
                                        <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                                            <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}" style="display: flex; align-items: center; justify-content: center;">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                            <span class="me-2"> - </span>
                                            <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px; display: flex; align-items: center; justify-content: center;">${data?.note ?? ''}</span>
                                        </div>
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
                                        <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                                            <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}" style="display: flex; align-items: center; justify-content: center;">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                            <span class="me-2"> - </span>
                                            <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px; display: flex; align-items: center; justify-content: center;">${data?.note ?? ''}</span>
                                        </div>
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
                                        <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                                            <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}" style="display: flex; align-items: center; justify-content: center;">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                                            <span class="me-2"> - </span>
                                            <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px; display: flex; align-items: center; justify-content: center;">${data?.note ?? ''}</span>
                                        </div>
                                        <button class="btn btn-warning btn-edit-business-money p-2" data-id="${5}">Sửa</button>
                                    </div>
                                    `;
                        }
                    },
                    // {
                    //     targets: 13,
                    //     data: 'money.6',
                    //     orderable: false,
                    //     render: function (data, type, row) {
                    //         return `
                    //                 <div class="d-flex align-items-center justify-content-between container-business-money">
                    //                     <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                    //                         <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                    //                         <span class="me-2"> - </span>
                    //                         <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px">${data?.note ?? ''}</span>
                    //                     </div>
                    //                     <button class="btn btn-warning btn-edit-business-money p-2" data-id="${6}">Sửa</button>
                    //                 </div>
                    //                 `;
                    //     }
                    // },
                    // {
                    //     targets: 14,
                    //     data: 'money.7',
                    //     orderable: false,
                    //     render: function (data, type, row) {
                    //         return `
                    //                 <div class="d-flex align-items-center justify-content-between container-business-money">
                    //                     <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                    //                         <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                    //                         <span class="me-2"> - </span>
                    //                         <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px">${data?.note ?? ''}</span>
                    //                     </div>
                    //                     <button class="btn btn-warning btn-edit-business-money p-2" data-id="${7}">Sửa</button>
                    //                 </div>
                    //                 `;
                    //     }
                    // },
                    // {
                    //     targets: 15,
                    //     data: 'money.8',
                    //     orderable: false,
                    //     render: function (data, type, row) {
                    //         return `
                    //                 <div class="d-flex align-items-center justify-content-between container-business-money">
                    //                     <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                    //                         <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                    //                         <span class="me-2"> - </span>
                    //                         <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px">${data?.note ?? ''}</span>
                    //                     </div>
                    //                     <button class="btn btn-warning btn-edit-business-money p-2" data-id="${8}">Sửa</button>
                    //                 </div>
                    //                 `;
                    //     }
                    // },
                    // {
                    //     targets: 16,
                    //     data: 'money.9',
                    //     orderable: false,
                    //     render: function (data, type, row) {
                    //         return `
                    //                 <div class="d-flex align-items-center justify-content-between container-business-money">
                    //                     <div class="d-flex align-items-center me-2 min-h-40px" style="${data.money != 0 ? 'background-color: #DBE2EF; border-radius: 4px;' : ''}">
                    //                         <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}">${data?.money?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }).replaceAll('.', ',').slice(0, -1) ?? 0}</span>
                    //                         <span class="me-2"> - </span>
                    //                         <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}" style="max-width: 125px">${data?.note ?? ''}</span>
                    //                     </div>
                    //                     <button class="btn btn-warning btn-edit-business-money p-2" data-id="${9}">Sửa</button>
                    //                 </div>
                    //                 `;
                    //     }
                    // },
                    {
                        targets: 13,
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
                                    <button class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                        Hành động
                                        <span class="svg-icon fs-5 m-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </button>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">

                                        <div class="menu-item px-3">
                                            <a href="javascript:void(0);" class="menu-link px-3 btn-edit" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit">
                                                Sửa
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="javascript:void(0);" class="menu-link px-3 btn-delete">
                                                Xóa
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="javascript:void(0);" class="menu-link px-3 btn-complete">
                                                Hoàn thành
                                            </a>
                                        </div>
                                    </div>
                                `;
                        },
                    },
                ],
            });

            // Re-init functions
            datatable.on('draw', function () {
                initComplete()
                initDelete()
                initEdit()
                initAction()
                // initViewMoney()
                initEditBusinessMoney()
                initEditPayExtra()
                KTMenu.createInstances()
                $('.paginate_button a').on('click', function () {
                    prevPhone = null;
                });
            })
            initEditSetting()
            handleSearchDatatable()
            handleUpdateNote()
        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    BusinessList.initDatatable();
}));
