"use strict";
var CustomerList = function () {
    let timeoutSearch;
    const drawer_note = document.querySelector("#drawer_note");
    const drawer_remind = document.querySelector("#drawer_remind");
    let dt_phone = ''
    const editModal = new bootstrap.Modal(document.querySelector('#modal_edit_customer'));
    const editCardModal = new bootstrap.Modal(document.querySelector('#modal_edit_card'));

    const headers = {
        Authorization: `Bearer ${token}`,
    };

    const updateToolbar = () => {
        const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
        const checkboxes = document.querySelectorAll(' tbody [type="checkbox"]');
        let anyChecked = false;
        let checkedCount = 0;

        checkboxes.forEach((checkbox => {
            if (checkbox.checked) {
                anyChecked = true;
                checkedCount++;
            }
        }));

        if (anyChecked) {
            selectedCount.innerHTML = checkedCount;
            baseToolbar.classList.add("d-none");
            selectedToolbar.classList.remove("d-none");
        } else {
            baseToolbar.classList.remove("d-none");
            selectedToolbar.classList.add("d-none");
        }

        return checkedCount;
    };
    const initDeleteSelected = () => {
        const checkboxes = document.querySelectorAll('[type="checkbox"]');
        const deleteSelectedBtn = document.querySelector('[data-kt-customer-table-select="delete_selected"]');
        checkboxes.forEach((checkbox => {
            checkbox.addEventListener("click", (function () {
                setTimeout((function () {
                    updateToolbar();
                }), 50);
            }));
        }));

        function messageStatus() {
            if (updateToolbar() === 1) {
                var customerName = document.querySelector('tbody [type="checkbox"]:checked')
                    .closest('tr').querySelector('td:nth-child(2)').innerText;
                return Swal.fire({
                    text: `Bạn có muốn xóa khách hàng ${customerName} không?`,
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Có, xóa!",
                    cancelButtonText: "Không, hủy bỏ",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                });
            } else if (updateToolbar() > 1) {
                return Swal.fire({
                    text: "Bạn có muốn xóa tất cả khách hàng đã chọn?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Có, xóa!",
                    cancelButtonText: "Không, hủy bỏ!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                });
            }
        }

        deleteSelectedBtn.addEventListener("click", (function () {
            messageStatus().then((function (result) {
                const headers = {
                    Authorization: `Bearer ${token}`,
                };
                if (result.value) {
                    var list_selected = [];
                    var checkboxed = document.querySelectorAll('tbody  [type="checkbox"]');
                    checkboxed.forEach((checkbox => {
                        if (checkbox.checked) {
                            list_selected = [...list_selected, checkbox.closest('tr')
                                .querySelector('input[name="customer-id"]').value];

                        }
                    }));
                    let data = {
                        list_selected: list_selected
                    }
                    console.log(data)
                    // const deletePromises = list_selected.map((customerID) => {
                    //     const url = delete_customer_route.replace('customer_id', customerID);
                    //     return axios.delete(url, {headers: headers});
                    // });
                    // Promise.all(deletePromises)
                    axios.delete(routes.deleteCustomers, { headers: headers, data: data })
                        .then((response) => {
                            var notiMessage = '';
                            if (updateToolbar() === 1) {
                                var customerName = document.querySelector('tbody [type="checkbox"]:checked')
                                    .closest('tr').querySelector('td:nth-child(2)').innerText;
                                notiMessage = `Khách hàng ${customerName} đã bị xóa!`;
                            } else {
                                notiMessage = 'Tất cả khách hàng đã chọn đã bị xóa!';

                            }
                            Swal.fire({
                                text: notiMessage,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                }
                            }).then((function () {
                                checkboxes.forEach((checkbox => {
                                    if (checkbox.checked) {
                                        dt_phone = null;
                                        datatable.row($(checkbox.closest("tbody tr"))).remove().draw();
                                    }
                                }));
                                document.querySelectorAll('[type="checkbox"]')[0].checked = false;
                                var baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
                                var selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
                                var selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
                                selectedCount.innerHTML = '';
                                baseToolbar.classList.remove("d-none");
                                selectedToolbar.classList.add('d-none');
                            }));
                        })
                } else if (result.dismiss === "cancel") {
                    Swal.fire({
                        text: "Không có khách hàng nào bị xóa.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                        }
                    });
                }
            }));
        }));
    };

    const handleSearchDatatable = () => {
        $('#customer_search').on("keyup", (function (e) {
            clearTimeout(timeoutSearch)
            timeoutSearch = setTimeout(function () {
                dt_phone = null;
                datatable.draw();
            }, 500)
        }));
    }

    const initNoteDrawer = () => {
        let drawer_btns = document.querySelectorAll('.drawer-note-btn');
        const drawer = KTDrawer.getInstance(drawer_note);
        drawer_btns.forEach((btn) => {
            btn.addEventListener('click', function () {
                drawer_note.querySelector('input[name="drawer-id"]').value = this.getAttribute('data-id')
                drawer_note.querySelector('textarea.drawer-note').value = this.getAttribute('data-note')
                drawer.toggle();
            })
        })
    }

    const saveNoteDrawer = () => {
        const drawer_save_note = document.querySelector('.drawer-save-note');
        drawer_save_note.addEventListener('click', function (e) {
            e.preventDefault()
            const id = drawer_note.querySelector('input[name="drawer-id"]').value
            const note = drawer_note.querySelector('textarea.drawer-note').value
            const headers = {
                Authorization: `Bearer ${token}`,
            };

            axios.post(routes.updateCardNote, { id: parseInt(id), note: note }, { headers: headers })
                .then((response) => {
                    if (response.data.code === 0) {
                        Swal.fire({
                            text: 'Cập nhật ghi chú thành công!',
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        }).then((function () {
                            // drawer.toggle();
                            dt_phone = null;
                            datatable.draw();
                        }));
                    } else {
                        Swal.fire({
                            text: 'Cập nhật ghi chú thất bại!',
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        })
                    }
                }).catch((error) => {
                    Swal.fire({
                        text: error.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                        }
                    })
                })
        })
    }

    const formatTime = (time) => {
        const dateTime = new Date(time);

        const year = dateTime.getFullYear();
        const month = String(dateTime.getMonth() + 1).padStart(2, "0");
        const day = String(dateTime.getDate()).padStart(2, "0");
        const hours = String(dateTime.getHours()).padStart(2, "0");
        const minutes = String(dateTime.getMinutes()).padStart(2, "0");

        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }

    const initRemindDrawer = () => {
        let drawer_btns = document.querySelectorAll('.drawer-remind-btn');
        const drawer = KTDrawer.getInstance(drawer_remind);

        drawer_btns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                drawer_remind.querySelector('input[name="card_id"]').value = data.id
                drawer_remind.querySelector('input[name="customer_id"]').value = data.customer.id

                const html = data.card_histories.map((history) => (
                    `
                    <div class="timeline-item">
                        <div class="timeline-label"></div>
                        <div class="timeline-badge">
                            <i class="fa fa-genderless text-primary fs-1"></i>
                        </div>
                        <div class="fw-mormal timeline-content text-muted ps-3">
                            <div class="fw-bold fs-6 text-gray-800">Nhắc bởi: ${history.user.name} - ${history.user.email}</div>
                            Thời gian: ${formatTime(history.created_at)}
                        </div>
                    </div>
                    `)
                )
                drawer_remind.querySelector('.timeline-label').innerHTML = html.join('');
                drawer.toggle();
            })
        })
    }

    const alertRemindDrawer = () => {
        const drawer_remind_alert = document.querySelector('.drawer-remind-alert');
        drawer_remind_alert.addEventListener('click', function (e) {
            e.preventDefault()
            const id = drawer_remind.querySelector('input[name="card_id"]').value
            const customer_id = drawer_remind.querySelector('input[name="customer_id"]').value

            axios.post(routes.remindCard, { id: id, customer_id: customer_id }, { headers: headers })
                .then((res) => {
                    if (res.data.code === 0) {
                        Swal.fire({
                            text: "Nhắc nợ thành công",
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            }
                        }).then(function () {
                            KTDrawer.getInstance(drawer_remind).toggle()
                            dt_phone = null;
                            datatable.draw()
                        })
                    } else {
                        Swal.fire({
                            text: res.data.data.join(", "),
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Quay lại ",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    }
                }).catch((err) => {
                    Swal.fire({
                        text: err.message,
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Quay lại ",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                });
        })
    }

    //EDIT
    const formEdit = document.querySelector('#edit_customer_form');
    const formEditCard = document.querySelector('#edit_card_form');
    let listCard = []

    const optionFormat = function (item) {
        if (!item.id) {
            return item.text;
        }

        let span = document.createElement('span');
        let bankLogo = ''
        if (item.bank_logo) {
            bankLogo = item.bank_logo
        } else {
            bankLogo = item.element.bank_logo
        }
        let template = `<img src="${bankLogo}" class="h-20px mb-1" />${item.text}`;

        span.innerHTML = template;

        return $(span);
    }

    const initEditGetBlankCards = function () {
        $("#select_edit_card").select2({
            templateSelection: optionFormat,
            templateResult: optionFormat,
            placeholder: {
                id: '',
                text: 'Chưa được chọn'
            },
            closeOnSelect: false,
            multiple: true,
            ajax: {
                url: routes.blankCards,
                dataType: 'json',
                delay: 250,
                type: "GET",
                headers: headers,
                processResults: function (data) {
                    const mappingData = $.map(data.data, function (item) {
                        return {
                            text: item.card_number,
                            id: item.id,
                            bank_logo: item.bank.logo,
                        };
                    });

                    const listCardData = listCard.map(function (item) {
                        return {
                            text: item.card_number,
                            id: item.id,
                            bank_logo: item.bank.logo
                        };
                    });

                    return {
                        results: [...mappingData, ...listCardData]
                    };
                }
            }
        });
    }

    const initEdit = () => {
        let btnEdits = document.querySelectorAll('.btn-edit-customer');

        btnEdits.forEach((btn) => {
            btn.addEventListener('click', function () {
                const row = btn.closest('tr')
                const data = datatable.row(row).data();
                console.log(data);
                formEdit.querySelector('input[name="id"]').value = data.customer_id ?? '';
                formEdit.querySelector('input[name="name"]').value = data.customer.name ?? '';
                formEdit.querySelector('input[name="phone"]').value = data.customer.phone ?? '';
                listCard = data.customer.cards
                $("#select_edit_card").empty()
                console.log(listCard);
                listCard.forEach(card => {
                    let opt = new Option(card.card_number, card.id, false, false);
                    opt.bank_logo = card.bank.logo
                    $("#select_edit_card").append(opt);
                });
                $("#select_edit_card").val(listCard.map((item) => item.id)).change();
            })
        })
    }

    const submitEditForm = () => {
        $('#edit_customer_form').submit(function (e) {
            e.preventDefault();
            const data = {
                id: formEdit.querySelector("input[name='id']").value,
                customer_name: formEdit.querySelector("input[name='name']").value,
                customer_phone: formEdit.querySelector("input[name='phone']").value,
                card_ids: $("#select_edit_card").select2("val"),
            };

            axios.post(routes.updateCustomer, data, { headers: headers })
                .then((res) => {
                    if (res.data.code === 0) {
                        Swal.fire({
                            text: "Sửa thông tin khách hàng thành công",
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                editModal.hide();
                                formEdit.reset();
                                $("#select_add_card").empty()
                                dt_phone = null;
                                datatable.draw()
                            }
                        })
                    } else {
                        Swal.fire({
                            text: res.data.data.join(", "),
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Quay lại ",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    }
                }).catch((err) => {
                    Swal.fire({
                        text: err.message,
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Quay lại ",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                });
        })
    }

    const initEditCard = () => {
        let btnEdits = document.querySelectorAll('.btn-edit-card');

        btnEdits.forEach((btn) => {
            btn.addEventListener('click', function () {
                const row = btn.closest('tr')
                const data = datatable.row(row).data();
                formEditCard.querySelector('input[name="id"]').value = data.id ?? '';
                formEditCard.querySelector('input[name="account_name"]').value = data.account_name ?? '';
                formEditCard.querySelector('input[name="card_number"]').value = data.card_number ?? '';
                formEditCard.querySelector('input[name="account_number"]').value = data.account_number ?? '';
                formEditCard.querySelector('input[name="login_info"]').value = data.login_info ?? '';
                formEditCard.querySelector('input[name="date_due"]').value = data.date_due ?? '';
                formEditCard.querySelector('input[name="date_return"]').value = data.date_return ?? '';
                formEditCard.querySelector('input[name="fee_percent"]').value = data.fee_percent ?? '';
                formEditCard.querySelector('input[name="total_money"]').value = data.total_money ?? '';
                formEditCard.querySelector('select[name="select_formality"]').value = data.formality ?? '';
                formEditCard.querySelector('input[name="pay_extra"]').value = data.pay_extra ?? '';
                formEditCard.querySelector('textarea[name="note"]').value = data.note ?? '';
            })
        })
    }

    const submitEditCardForm = () => {
        $('#edit_card_form').submit(function (e) {
            e.preventDefault();
            let data = {
                id: formEditCard.querySelector(
                    "input[name='id']").value,
                account_name: formEditCard.querySelector(
                    'input[name="account_name"]'
                ).value,
                card_number: formEditCard.querySelector(
                    'input[name="card_number"]'
                ).value,
                account_number: formEditCard.querySelector(
                    'input[name="account_number"]'
                ).value,
                date_due: formEditCard.querySelector(
                    'input[name="date_due"]'
                ).value,
                date_return: formEditCard.querySelector(
                    'input[name="date_return"]'
                ).value,
                login_info: formEditCard.querySelector(
                    'input[name="login_info"]'
                ).value,
                bank_code: formEditCard.querySelector(
                    'select[name="bank_code"]'
                ).value,
                fee_percent: formEditCard.querySelector(
                    'input[name="fee_percent"]'
                ).value,
                total_money: formEditCard.querySelector(
                    'input[name="total_money"]'
                ).value,
                formality: formEditCard.querySelector(
                    'select[name="select_formality"]'
                ).value,
                pay_extra: formEditCard.querySelector(
                    'input[name="pay_extra"]'
                ).value,
                note: formEditCard.querySelector(
                    'textarea[name="note"]'
                ).value,
            };

            axios.post(
                routes.editCard, data,
                { headers: headers })
                .then((res) => {
                    if (res.data.code === 0) {
                        Swal.fire({
                            text: "Sửa thông tin thẻ thành công",
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                editCardModal.hide();
                                formEditCard.reset();
                                $("#select_add_card").empty()
                                dt_phone = null;
                                datatable.draw()
                            }
                        })
                    } else {
                        Swal.fire({
                            text: res.data.data.join(", "),
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Quay lại ",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    }
                }).catch((err) => {
                    Swal.fire({
                        text: err.message,
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Quay lại ",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                });
        })
    }

    $("#modal_edit_card_close").click(function () {
        editCardModal.hide();
    })

    $("#modal_edit_card_cancel").click(function () {
        editCardModal.hide();
    })

    $("#modal_edit_customer_close").click(function () {
        editModal.hide();
    })

    $("#modal_edit_customer_cancel").click(function () {
        editModal.hide();
    })



    return {
        initDatatable: async function () {
            datatable = $("#kt_customers_table").DataTable({
                fixedColumns: {
                    leftColumns: 1,
                    // rightColumns: 1

                },
                searchDelay: 500,
                processing: true,
                serverSide: true,
                // order: [
                //     [2, 'desc']
                // ],
                stateSave: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    className: 'row-selected'
                },
                ajax: {
                    url: routes.getAllCustomers,
                    type: "POST",
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", `Bearer ${token}`);
                    },
                    data: function (d) {
                        d.search = $('#customer_search').val();
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        data: 'customer.id',
                        orderable: false,
                        render: function (data, type, row) {
                            if (row.customer.phone !== dt_phone && type === 'display') {
                                return `
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="customer-id" value="${data}"/>
                                    </div>`;
                            }
                            return `<div></div>`
                        }
                    },
                    {
                        targets: 1,
                        data: 'customer',
                        orderable: false,
                        render: function (data, type, row) {
                            if (row.customer.phone !== dt_phone && type === 'display') {
                                dt_phone = row.customer.phone
                                return `<span>${row.customer.name} - ${row.customer.phone}</span>`
                            }
                            return `<span></span>`
                        }
                    },
                    {
                        targets: 2,
                        data: 'bank.shortName',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex flex-column align-items-center">
                                        <img src="https://api.vietqr.io/img/${row.bank_code}.png" class="h-30px" alt="${row.bank_code}">
                                        ${data ?? ''}
                                    </div>
                                    `;
                        }
                    },
                    {
                        targets: 3,
                        data: 'card_number',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 4,
                        data: 'account_number',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        data: 'login_info',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<p class="mb-0">${data ?? ''}</p>`;
                        }
                    },
                    {
                        targets: 6,
                        data: 'date_due',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data.split("-").reverse().join("-") ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 7,
                        data: 'date_return',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data.split("-").reverse().join("-") ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 8,
                        data: 'note',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="d-flex justify-content-between align-items-center">
                                        <p class="text-truncate mb-0 me-2" style="max-width: 200px">${data ?? ''}</p>
                                        <button class="btn btn-primary drawer-note-btn" data-id="${row.id}" data-note="${data ?? ''}">Xem</button>
                                    </div>`;
                        }
                    },
                    {
                        targets: 9,
                        data: 'card_histories',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data.length > 0 ? 'Đã nhắc' : 'Chưa nhắc'}</span>`;
                        }
                    },
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        className: 'text-end',
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
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="javascript:void(0);" class="menu-link px-3 btn-edit-customer" data-bs-toggle="modal"
                                            data-bs-target="#modal_edit_customer">
                                                Sửa khách hàng
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="javascript:void(0);" class="menu-link px-3 btn-edit-card" data-bs-toggle="modal"
                                            data-bs-target="#modal_edit_card">
                                                Sửa thẻ
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="javascript:void(0);" class="menu-link px-3" data-kt-docs-table-filter="delete_row">
                                                Xóa
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="javascript:void(0);" class="menu-link px-3 drawer-remind-btn">
                                                Nhắc nợ
                                            </a>
                                        </div>
                                    </div>
                                `;
                        },
                    },
                ]
            });

            // Re-init functions
            datatable.on('draw', function () {
                initDeleteSelected();
                handleSearchDatatable()
                initNoteDrawer()
                initRemindDrawer()
                initEdit()
                initEditCard()
                KTMenu.createInstances()
            })
            handleSearchDatatable()
            initEditGetBlankCards()
            saveNoteDrawer()
            submitEditForm()
            submitEditCardForm()
            alertRemindDrawer()
        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    CustomerList.initDatatable();
}));
