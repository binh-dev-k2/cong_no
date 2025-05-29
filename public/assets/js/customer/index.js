"use strict";

// Common utilities and constants
const headers = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
};

// Format utility functions
const formatNumber = (number) => {
    const str = number.toString();
    const formattedStr = str.replace(/(.{4})/g, '$1 ');
    return formattedStr.trim();
};

const formatDate = (time) => {
    const dateTime = new Date(time);
    const year = dateTime.getFullYear();
    const month = String(dateTime.getMonth() + 1).padStart(2, "0");
    const day = String(dateTime.getDate()).padStart(2, "0");
    return `${day}-${month}-${year}`;
};

const formatTime = (time) => {
    const dateTime = new Date(time);
    const year = dateTime.getFullYear();
    const month = String(dateTime.getMonth() + 1).padStart(2, "0");
    const day = String(dateTime.getDate()).padStart(2, "0");
    const hours = String(dateTime.getHours()).padStart(2, "0");
    const minutes = String(dateTime.getMinutes()).padStart(2, "0");
    return `${day}/${month}/${year} ${hours}:${minutes}`;
};

// Format options for select2 controls
const optionFormatCard = function (item) {
    if (!item.id) return item.text;
    let span = document.createElement('span');
    let template = `<img src="${item.element.getAttribute('data-kt-select2-country')}" class="h-20px mb-1" />${item.text}`;
    span.innerHTML = template;
    return $(span);
};

const optionFormat = function (item) {
    if (!item.id) return item.text;
    let span = document.createElement('span');
    let bankLogo = item.bank_logo || item.element?.bank_logo || item.element?.getAttribute("data-kt-select2-country");
    let template = `<img src="${bankLogo}" class="h-20px mb-1" />${item.text}`;
    span.innerHTML = template;
    return $(span);
};

// CustomerList module
const CustomerList = function () {
    let timeoutSearch;
    const drawerNote = document.querySelector("#drawer_note");
    const drawerRemind = document.querySelector("#drawer_remind");
    const drawerLoginInfo = document.querySelector("#drawer_login_info");
    const formEditCard = document.querySelector('#edit_card_form');
    const formEdit = document.querySelector('#edit_customer_form');
    let prevPhone = '';
    let listCard = [];

    // Get all customer checkboxes
    const getCustomerCheckboxes = () => document.querySelectorAll('tbody [type="checkbox"]');

    // Update toolbar when checkboxes are selected
    const updateToolbar = () => {
        const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
        const checkboxes = getCustomerCheckboxes();
        let checkedCount = 0;
        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) checkedCount++;
        });
        if (checkedCount > 0) {
            selectedCount.innerHTML = checkedCount;
            baseToolbar?.classList.add("d-none");
            selectedToolbar?.classList.remove("d-none");
        } else {
            baseToolbar?.classList.remove("d-none");
            selectedToolbar?.classList.add("d-none");
        }
        return checkedCount;
    };

    // Show delete confirmation
    const showDeleteConfirm = (count) => {
        if (count === 1) {
            const customerName = document.querySelector('tbody [type="checkbox"]:checked')
                ?.closest('tr')?.querySelector('td:nth-child(2)')?.innerText;
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
        } else if (count > 1) {
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
    };

    // Initialize delete selected customers event
    const initDeleteSelected = () => {
        const checkboxes = document.querySelectorAll('[type="checkbox"]');
        const deleteSelectedBtn = document.querySelector('[data-kt-customer-table-select="delete_selected"]');

        if (!deleteSelectedBtn) return;

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("click", () => {
                setTimeout(updateToolbar, 50);
            });
        });

        deleteSelectedBtn.addEventListener("click", () => {
            const count = updateToolbar();
            showDeleteConfirm(count).then((result) => {
                if (result.value) {
                    let listSelected = [];
                    getCustomerCheckboxes().forEach((checkbox) => {
                        if (checkbox.checked) {
                            listSelected.push(
                                checkbox.closest('tr').querySelector('input[name="customer-id"]').value
                            );
                        }
                    });

                    let data = { list_selected: listSelected };
                    axios.delete(routes.deleteCustomers, { headers, data })
                        .then(() => {
                            let notiMessage = count === 1
                                ? `Khách hàng ${document.querySelector('tbody [type="checkbox"]:checked').closest('tr').querySelector('td:nth-child(2)').innerText} đã bị xóa!`
                                : 'Tất cả khách hàng đã chọn đã bị xóa!';

                            Swal.fire({
                                text: notiMessage,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                }
                            }).then(() => {
                                checkboxes.forEach((checkbox) => {
                                    if (checkbox.checked) {
                                        prevPhone = null;
                                        datatable.row($(checkbox.closest("tbody tr"))).remove().draw();
                                    }
                                });
                                document.querySelectorAll('[type="checkbox"]')[0].checked = false;
                                updateToolbar();
                            });
                        });
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
            });
        });
    };

    // Handle search
    const handleSearchDatatable = () => {
        $('#customer_search').on("keyup", function() {
            clearTimeout(timeoutSearch);
            timeoutSearch = setTimeout(function () {
                prevPhone = null;
                datatable.draw();
            }, 500);
        });
    };

    // Initialize note drawer
    const initNoteDrawer = () => {
        let drawerBtns = document.querySelectorAll('.drawer-note-btn');
        const drawer = KTDrawer.getInstance(drawerNote);

        if (!drawer) return;

        drawerBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                drawerNote.querySelector('input[name="drawer-id"]').value = this.getAttribute('data-id');
                drawerNote.querySelector('textarea.drawer-note').value = this.getAttribute('data-note');
                drawer.toggle();
            });
        });
    };

    // Initialize login info drawer
    const initLoginInfoDrawer = () => {
        let drawerBtns = document.querySelectorAll('.drawer-login-info-btn');
        const drawer = KTDrawer.getInstance(drawerLoginInfo);

        if (!drawer) return;

        drawerBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                drawerLoginInfo.querySelector('textarea[name="login_info"]').value = data.login_info ?? '';
                drawer.toggle();
            });
        });
    };

    // Save note
    const saveNoteDrawer = () => {
        const drawerSaveNote = document.querySelector('.drawer-save-note');

        if (!drawerSaveNote) return;

        drawerSaveNote.addEventListener('click', function (e) {
            e.preventDefault();
            const id = drawerNote.querySelector('input[name="drawer-id"]').value;
            const note = drawerNote.querySelector('textarea.drawer-note').value;

            axios.post(routes.updateCardNote, { id: parseInt(id), note }, { headers })
                .then((response) => {
                    if (response.data.code === 0) {
                        Swal.fire({
                            text: 'Cập nhật ghi chú thành công!',
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        }).then(() => {
                            prevPhone = null;
                            datatable.draw();
                        });
                    } else {
                        Swal.fire({
                            text: 'Cập nhật ghi chú thất bại!',
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        });
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
                    });
                });
        });
    };

    // Initialize remind drawer
    const initRemindDrawer = () => {
        let drawerBtns = document.querySelectorAll('.drawer-remind-btn');
        const drawer = KTDrawer.getInstance(drawerRemind);

        if (!drawer) return;

        drawerBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                drawerRemind.querySelector('input[name="card_id"]').value = data.id;
                drawerRemind.querySelector('input[name="customer_id"]').value = data.customer.id;

                const html = data.card_histories.reverse().map((history) => (
                    `<div class="timeline-item">
                        <div class="timeline-label"></div>
                        <div class="timeline-badge">
                            <i class="fa fa-genderless text-primary fs-1"></i>
                        </div>
                        <div class="fw-mormal timeline-content text-muted ps-3">
                            <div class="fw-bold fs-6 text-gray-800">Nhắc bởi: ${history.user.name} - ${history.user.email}</div>
                            Thời gian: ${formatTime(history.created_at)}
                        </div>
                    </div>`
                ));

                drawerRemind.querySelector('.timeline-label').innerHTML = html.join('');
                drawer.toggle();
            });
        });
    };

    // Send remind alert
    const alertRemindDrawer = () => {
        const drawerRemindAlert = document.querySelector('.drawer-remind-alert');

        if (!drawerRemindAlert) return;

        drawerRemindAlert.addEventListener('click', function (e) {
            e.preventDefault();
            const id = document.querySelector('#drawer_remind input[name="card_id"]').value;
            const customer_id = drawerRemind.querySelector('input[name="customer_id"]').value;

            axios.post(routes.remindCard, { id, customer_id }, { headers })
                .then((res) => {
                    if (res.data.code === 0) {
                        Swal.fire({
                            text: "Nhắc nợ thành công",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            }
                        }).then(function () {
                            KTDrawer.getInstance(drawerRemind).toggle();
                            prevPhone = null;
                            datatable.draw();
                        });
                    } else {
                        Swal.fire({
                            text: res.data.data.join(", "),
                            icon: "error",
                            buttonsStyling: false,
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
                        buttonsStyling: false,
                        confirmButtonText: "Quay lại ",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                });
        });
    };

    // Initialize datatable
    const initDatatable = async function () {
        datatable = $("#kt_customers_table").DataTable({
            lengthMenu: [10, 20, 50, 100],
            pageLength: 50,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: routes.getAllCustomers,
                type: "POST",
                beforeSend: function (request) {
                    request.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                },
                data: function (d) {
                    d.search = $('#customer_search').val();
                    d.view_type = $('input[name="view_type"]:checked').val();
                }
            },
            columnDefs: [
                {
                    targets: 0,
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    targets: 1,
                    data: 'customer',
                    className: 'text-center',
                    orderable: false,
                    render: function (data, type, row) {
                        if (row.customer.phone !== prevPhone && type === 'display') {
                            prevPhone = row.customer.phone;
                            const phone = row.customer.phone.startsWith('@') ? row.customer.phone.substring(1) : row.customer.phone;
                            const url = row.customer.phone.startsWith('@') ? `https://t.me/${phone}` : `https://zalo.me/${phone}`;
                            return `
                                <div class="d-flex flex-column align-items-center">
                                    <div class="fw-bold mb-1">${row.customer.name}</div>
                                    <a href="${url}" target="_blank" class="text-primary text-hover-primary">
                                        <i class="bi ${row.customer.phone.startsWith('@') ? 'bi-telegram' : 'bi-chat-dots'} me-1"></i>
                                        ${phone}
                                    </a>
                                </div>`;
                        }
                        return `<div class="empty-cell"></div>`;
                    }
                },
                {
                    targets: 2,
                    data: 'bank',
                    orderable: false,
                    render: function (data, type, row) {
                        return `<div class="d-flex flex-column align-items-center">
                                    <img src="${data.logo}" class="h-40px" alt="${data.code}">
                                    ${data.shortName ?? ''}
                                </div>`;
                    }
                },
                {
                    targets: 3,
                    data: 'account_name',
                    orderable: false,
                    render: function (data, type, row) {
                        return `<span class="text-nowrap">${data ?? ''}</span>`;
                    }
                },
                {
                    targets: 4,
                    data: 'card_number',
                    orderable: false,
                    render: function (data, type, row) {
                        const timeExpired = row.month_expired ? `${row.month_expired}-${row.year_expired}` : '';
                        const elementDateReturn = row.date_return ? `
                            <div class="position-relative">
                                <i class="fas fa-info-circle text-info cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Ngày trả thẻ: ${formatDate(row.date_return)}"></i>
                            </div>
                        ` : '';

                        const elementNote = row.note ? `
                            <div class="position-relative ms-1">
                                <i class="fas fa-sticky-note text-warning cursor-pointer"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="${row.note}"></i>
                            </div>
                        ` : '';

                        return `
                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="px-3 py-2 text-center rounded-3 mb-1 text-nowrap bg-light-primary">
                                        ${data ? formatNumber(data) : ''}
                                    </div>
                                    ${elementDateReturn}
                                    ${elementNote}
                                </div>
                                ${timeExpired ? `
                                    <div class="text-muted fs-7">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        Hết hạn: ${timeExpired}
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    }
                },
                {
                    targets: 5,
                    data: 'account_number',
                    orderable: false,
                    render: function (data, type, row) {
                        return `<span>${data ? formatNumber(data) : ''}</span>`;
                    }
                },
                {
                    targets: 6,
                    data: 'date_due',
                    orderable: true,
                    className: 'text-center',
                    render: function (data, type, row) {
                        return `<span>${data ?? 'R'}</span>`;
                    }
                },
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-center',
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
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true">
                                    ${row.date_return ?
                                (`<div class="menu-item px-3">
                                            <a href="javascript:void(0);" class="menu-link px-3">
                                                ${formatDate(row.date_return)}
                                            </a>
                                        </div>`) : ''}
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0);" class="menu-link px-3 btn-edit-customer" data-bs-toggle="modal"
                                        data-bs-target="#modal_edit_customer">
                                            Sửa khách hàng
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0);" class="menu-link px-3 btn-add-card-to-customer" >
                                            Thêm thẻ
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0);" class="menu-link px-3 btn-edit-card" >
                                            Sửa thẻ
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0);" class="menu-link px-3 btn-delete_card"  data-kt-docs-table-filter="delete_row">
                                            Xóa thẻ
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0);" class="menu-link px-3 drawer-remind-btn">
                                            Nhắc nợ
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0);" class="menu-link px-3 drawer-login-info-btn">
                                            TT đăng nhập
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0);" class="menu-link px-3 drawer-note-btn" data-id="${row.id}" data-note="${row.note ?? ''}">
                                            Ghi chú
                                        </a>
                                    </div>
                                </div>
                            `;
                    },
                },
            ]
        });

        // Re-init functions on draw
        datatable.on('draw', function () {
            initDeleteSelected();
            handleSearchDatatable();
            initNoteDrawer();
            initRemindDrawer();
            initLoginInfoDrawer();
            initEdit();
            initEditCard();
            addCard();
            deleteCard();
            KTMenu.createInstances();
            $('.paginate_button a').on('click', function () {
                prevPhone = null;
            });
        });

        handleSearchDatatable();
        initEditGetBlankCards();
        saveNoteDrawer();
        submitEditForm();
        submitEditCardForm();
        alertRemindDrawer();
    };

    // Initialize edit customer
    const initEdit = () => {
        let btnEdits = document.querySelectorAll('.btn-edit-customer');
        btnEdits.forEach((btn) => {
            btn.addEventListener('click', function () {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                formEdit.querySelector('input[name="id"]').value = data.customer_id ?? '';
                formEdit.querySelector('input[name="name"]').value = data.customer.name ?? '';
                formEdit.querySelector('input[name="phone"]').value = data.customer.phone ?? '';
                listCard = data.customer.cards;
                $("#select_edit_card").empty();
                listCard.forEach(card => {
                    let opt = new Option(card.card_number, card.id, false, false);
                    opt.bank_logo = card.bank.logo;
                    $("#select_edit_card").append(opt);
                });
                $("#select_edit_card").val(listCard.map((item) => item.id)).change();
            });
        });
    };

    // Initialize edit card
    const initEditCard = () => {
        let btnEdits = document.querySelectorAll('.btn-edit-card');
        btnEdits.forEach((btn) => {
            btn.addEventListener('click', function () {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                formEditCard.querySelector('input[name="id"]').value = data.id ?? '';
                formEditCard.querySelector('input[name="account_name"]').value = data.account_name ?? '';
                formEditCard.querySelector('input[name="card_number"]').value = data.card_number ?? '';
                formEditCard.querySelector('input[name="account_number"]').value = data.account_number ?? '';
                formEditCard.querySelector('input[name="login_info"]').value = data.login_info ?? '';
                formEditCard.querySelector('input[name="date_due"]').value = data.date_due ?? '';
                formEditCard.querySelector('select[name="bank_code"]').value = data.bank_code ?? '';
                formEditCard.querySelector('input[name="date_return"]').value = data.date_return ?? '';
                formEditCard.querySelector('input[name="fee_percent"]').value = data.fee_percent ?? '';
                formEditCard.querySelector('textarea[name="note"]').value = data.note ?? '';
                formEditCard.querySelector('input[name="month_expired"]').value = data.month_expired ?? '';
                formEditCard.querySelector('input[name="year_expired"]').value = data.year_expired ?? '';
                $('#modal_edit_card').modal('show');
            });
        });
    };

    // Add card to customer
    const addCard = () => {
        let btnAddCards = document.querySelectorAll('.btn-add-card-to-customer');
        btnAddCards.forEach((btnAdd) => {
            btnAdd.addEventListener('click', function () {
                const row = btnAdd.closest('tr');
                const data = datatable.row(row).data();
                $('#kt_modal_add_card').modal('show');
                $('#customer_id').val(data.customer_id);
            });
        });
    };

    // Delete card
    const deleteCard = () => {
        let btnDeleteCards = document.querySelectorAll('.btn-delete_card');
        btnDeleteCards.forEach((btnDel) => {
            const row = btnDel.closest('tr');
            const data = datatable.row(row).data();

            btnDel.addEventListener('click', function () {
                Swal.fire({
                    text: `Bạn có chắc chắn muốn xóa thẻ có số thẻ ${data.card_number} không?`,
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Có, xóa!",
                    cancelButtonText: "Không, hủy bỏ",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(routes.deleteCard, { headers, data: { id: data.id } })
                            .then(() => {
                                Swal.fire({
                                    text: "Xóa thẻ thành công",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        prevPhone = null;
                                        datatable.row($(btnDel.closest("tbody tr"))).remove().draw();
                                    }
                                });
                            });
                    }
                });
            });
        });
    };

    // Submit edit customer form
    const submitEditForm = () => {
        $('#edit_customer_form').submit(function (e) {
            e.preventDefault();
            const data = {
                id: formEdit.querySelector("input[name='id']").value,
                customer_name: formEdit.querySelector("input[name='name']").value,
                customer_phone: formEdit.querySelector("input[name='phone']").value,
                card_ids: $("#select_edit_card").select2("val"),
            };

            axios.post(routes.updateCustomer, data, { headers })
                .then((res) => {
                    if (res.data.code === 0) {
                        Swal.fire({
                            text: "Sửa thông tin khách hàng thành công",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            }
                        }).then(function () {
                            $("#modal_edit_customer").modal('hide');
                            $('#edit_customer_form').trigger('reset');
                            $("#select_add_card").empty();
                            prevPhone = null;
                            datatable.draw();
                        });
                    } else {
                        Swal.fire({
                            text: res.data.data.join(", "),
                            icon: "error",
                            buttonsStyling: false,
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
                        buttonsStyling: false,
                        confirmButtonText: "Quay lại ",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                });
        });
    };

    // Submit edit card form
    const submitEditCardForm = () => {
        if (formEditCard) {
            formEditCard.querySelector('[name="date_return"]')?.flatpickr({
                enableTime: false,
                dateFormat: "Y-m-d",
                locale: "vn",
            });

            $('#modal_edit_card').on("show.bs.modal", function () {
                $(this).find('#select_bank_list_edit').select2({
                    templateSelection: optionFormatCard,
                    templateResult: optionFormatCard,
                    minimumResultsForSearch: 0,
                    dropdownParent: $("#select_bank_edit")
                });
            });

            $('#edit_card_form').submit(function (e) {
                e.preventDefault();
                let data = {
                    id: formEditCard.querySelector("input[name='id']").value,
                    account_name: formEditCard.querySelector('input[name="account_name"]').value,
                    card_number: formEditCard.querySelector('input[name="card_number"]').value,
                    account_number: formEditCard.querySelector('input[name="account_number"]').value,
                    date_due: formEditCard.querySelector('input[name="date_due"]').value,
                    date_return: formEditCard.querySelector('input[name="date_return"]').value,
                    fee_percent: formEditCard.querySelector('input[name="fee_percent"]').value,
                    login_info: formEditCard.querySelector('input[name="login_info"]').value,
                    bank_code: formEditCard.querySelector('select[name="bank_code"]').value,
                    note: formEditCard.querySelector('textarea[name="note"]').value,
                    month_expired: formEditCard.querySelector('input[name="month_expired"]').value,
                    year_expired: formEditCard.querySelector('input[name="year_expired"]').value,
                };

                axios.post(routes.editCard, data, { headers })
                    .then((res) => {
                        if (res.data.code === 0) {
                            Swal.fire({
                                text: "Sửa thông tin thẻ thành công",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                }
                            });

                            $("#modal_edit_card").modal('hide');
                            formEditCard.reset();
                            $("#select_add_card").empty();
                            prevPhone = null;
                            datatable.draw();
                        } else {
                            Swal.fire({
                                text: res.data.data.join(", "),
                                icon: "error",
                                buttonsStyling: false,
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
                            buttonsStyling: false,
                            confirmButtonText: "Quay lại ",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    });
            });
        }
    };

    // Initialize edit blank cards
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
    };

    // Set up modal close buttons
    const initModalButtons = () => {
        $("#modal_edit_card_close, #modal_edit_card_cancel").click(function () {
            $("#modal_edit_card").modal('hide');
        });

        $("#modal_edit_customer_close, #modal_edit_customer_cancel").click(function () {
            $("#modal_edit_customer").modal('hide');
        });

        $('[data-bs-toggle="tab"]').on('click', function () {
            $(this).find('input[type="radio"]').prop('checked', true);
            prevPhone = null;
            datatable.draw();
        });
    };

    return {
        initDatatable,
        init: function() {
            initModalButtons();
        }
    };
}();

// Add Customer functionality
const KTModalCustomersAdd = (function () {
    let btn_submit, btn_cancel, btn_close, formValidate, form, i, btn_add_customer;

    // Use the global optionFormat function for consistency
    const initGetBlankCards = function () {
        $("#customer_select_card").select2({
            templateSelection: optionFormat,
            templateResult: optionFormat,
            placeholder: {
                id: '',
                text: 'None Selected'
            },
            closeOnSelect: false,
            multiple: true,
            ajax: {
                url: routes.blankCards,
                dataType: 'json',
                delay: 250,
                type: "GET",
                cache: true,
                headers,
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.card_number,
                                id: item.id,
                                bank_logo: item.bank.logo
                            };
                        })
                    };
                }
            }
        });
    };

    return {
        init: function () {
            i = new bootstrap.Modal(document.querySelector("#kt_modal_add_customer"));
            btn_add_customer = document.querySelector("#btn-add-customer");
            form = document.querySelector("#kt_modal_add_customer_form");
            btn_submit = form?.querySelector("#kt_modal_add_customer_submit");
            btn_cancel = form?.querySelector("#kt_modal_add_customer_cancel");
            btn_close = form?.querySelector("#kt_modal_add_customer_close");

            if (!form) return;

            document.querySelector("#kt_modal_add_customer")?.addEventListener('hidden.bs.modal', function () {
                form.reset();
                $("#customer_select_card").empty();
            });

            btn_cancel?.addEventListener('click', function () {
                form.reset();
                $("#customer_select_card").empty();
                i.hide();
            });

            btn_close?.addEventListener('click', function () {
                form.reset();
                $("#customer_select_card").empty();
                i.hide();
            });

            btn_add_customer?.addEventListener('click', function () {
                $("#customer_select_card").empty();
                initGetBlankCards();
            });

            formValidate = FormValidation.formValidation(form, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: "Tên khách hàng không được để trống",
                            },
                        },
                    },
                    phone: {
                        validators: {
                            notEmpty: {
                                message: "Số điện thoại không được để trống",
                            },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                    }),
                },
            });

            btn_submit?.addEventListener("click", function (e) {
                e.preventDefault();
                formValidate && formValidate.validate().then((status) => {
                    if (status === "Valid") {
                        let data = {
                            customer_name: form.querySelector("input[name='name']").value,
                            customer_phone: form.querySelector("input[name='phone']").value,
                            card_ids: $("#customer_select_card").select2("val"),
                        };

                        axios.post(routes.storeCustomer, data, { headers })
                            .then((res) => {
                                if (res.data.code === 0) {
                                    Swal.fire({
                                        text: "Thêm khách hàng thành công",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        }
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            i.hide();
                                            form.reset();
                                            $("#customer_select_card").empty();
                                            datatable.draw();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        text: res.data.data.join(", "),
                                        icon: "error",
                                        buttonsStyling: false,
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
                                    buttonsStyling: false,
                                    confirmButtonText: "Quay lại ",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                });
                            });
                    }
                });
            });
        }
    };
})();

// Add Card functionality
const FormAddCard = () => {
    const modalElement = document.querySelector('#kt_modal_add_card');
    const form = document.querySelector('#kt_modal_add_card_form');
    const btnSubmit = form?.querySelector('#submit_add_new_card');
    const btnCancel = document.querySelector('#btn_card_cancel');
    const btnClose = document.querySelector('#modal_add_card_close');
    const addModal = modalElement ? new bootstrap.Modal(modalElement) : null;

    // Handle modal hidden
    const handleModalHidden = () => {
        if (!form) return;
        form.reset();
        $('#customer_id').val('');
    };

    // Initialize validation
    let formValidate;
    const initValidation = () => {
        if (!form) return;

        formValidate = FormValidation.formValidation(form, {
            fields: {
                card_number: {
                    validators: {
                        notEmpty: { message: "Số thẻ là bắt buộc" },
                        numeric: { message: "Số thẻ phải là số" },
                        stringLength: { message: "Số thẻ phải có đúng 15 hoặc 16 chữ số", min: 15, max: 16 },
                    },
                },
                account_name: { validators: { notEmpty: { message: "Chủ tài khoản là bắt buộc" } } },
                date_due: { validators: { between: { min: 1, max: 31, message: "Ngày đáo hạn phải từ 1 đến 31" } } },
                date_return: { validators: { date: { format: "YYYY-MM-DD", message: "Ngày trả phải có định dạng YYYY-MM-DD" } } },
                bank_code: { validators: { notEmpty: { message: "ID Ngân hàng là bắt buộc" } } },
                fee_percent: { validators: { notEmpty: { message: "Phần trăm phí là bắt buộc" } } },
                month_expired: { validators: { between: { min: 1, max: 12, message: "Tháng hết hạn thẻ phải nằm trong khoảng 1 tới 12" } } },
                year_expired: { validators: { between: { min: 2000, max: 3000, message: "Năm hết hạn thẻ phải bắt đầu từ 2000" } } },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger({
                    event: {
                        input: false,
                        blur: true,
                        change: false,
                        submit: true,
                    },
                }),
                bootstrap5: new FormValidation.plugins.Bootstrap5({ rowSelector: ".fv-row" }),
            },
        });
    };

    // Handle submit
    const handleSubmit = (e) => {
        e.preventDefault();
        if (!btnSubmit || !form || !formValidate || !addModal) return;

        btnSubmit.setAttribute("data-kt-indicator", "on");
        formValidate.validate().then((status) => {
            if (status === "Valid") {
                let data = {
                    customer_id: form.querySelector('input[name="customer_id"]').value ? Number(form.querySelector('input[name="customer_id"]').value) : null,
                    account_name: form.querySelector('input[name="account_name"]').value,
                    card_number: form.querySelector('input[name="card_number"]').value,
                    account_number: form.querySelector('input[name="account_number"]').value,
                    date_due: form.querySelector('input[name="date_due"]').value,
                    date_return: form.querySelector('input[name="date_return"]').value,
                    login_info: form.querySelector('input[name="login_info"]').value,
                    bank_code: form.querySelector('select[name="bank_code"]').value,
                    fee_percent: form.querySelector('input[name="fee_percent"]').value,
                    note: form.querySelector('textarea[name="note"]').value,
                    month_expired: form.querySelector('input[name="month_expired"]').value,
                    year_expired: form.querySelector('input[name="year_expired"]').value,
                };

                axios.post(routes.storeCard, data, { headers })
                    .then((response) => {
                        btnSubmit.removeAttribute("data-kt-indicator");
                        if (response.status === 200 && response.data.code != 1) {
                            Swal.fire({
                                text: "Thêm thẻ thành công",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Thành công!",
                                customClass: { confirmButton: "btn btn-primary" },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.reset();
                                    form.querySelector('input[name="customer_id"]').value = '';
                                    // Remove validation state
                                    $(form).find('input, select, textarea').removeClass('is-valid is-invalid');
                                    addModal.hide();
                                    datatable.draw();
                                }
                            });
                        } else {
                            Swal.fire({
                                text: response.data.data[0],
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Quay lại ",
                                customClass: { confirmButton: "btn btn-primary" },
                            });
                        }
                    })
                    .catch((err) => {
                        btnSubmit.removeAttribute("data-kt-indicator");
                        if (err.response && err.response.status === 422) {
                            let messages = err.response.data.errors;
                            let errorMessage = [];
                            for (const key in messages) {
                                if (Object.hasOwnProperty.call(messages, key)) {
                                    errorMessage.push(messages[key]);
                                }
                            }
                            Swal.fire({
                                text: errorMessage.join(", "),
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Quay lại ",
                                customClass: { confirmButton: "btn btn-primary" },
                            });
                        } else {
                            Swal.fire({
                                text: err.message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Quay lại ",
                                customClass: { confirmButton: "btn btn-primary" },
                            });
                        }
                    });
            } else {
                btnSubmit.removeAttribute("data-kt-indicator");
                Swal.fire({
                    text: "Dữ liệu không hợp lệ, vui lòng kiểm tra lại",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Quay lại",
                    customClass: { confirmButton: "btn btn-primary" },
                }).then((result) => {
                    if (result.isConfirmed) {
                        KTUtil.scrollTop();
                    }
                });
            }
        });
    };

    // Handle cancel/close
    const handleCancelOrClose = (event) => {
        event.preventDefault();
        if (addModal) addModal.hide();
    };

    return {
        init: function () {
            if (!modalElement || !form || !btnSubmit) return;

            modalElement.addEventListener('hidden.bs.modal', handleModalHidden);
            btnSubmit.addEventListener('click', handleSubmit);
            btnCancel?.addEventListener('click', handleCancelOrClose);
            btnClose?.addEventListener('click', handleCancelOrClose);
            initValidation();

            // Add datepicker
            $(form.querySelector('[name="date_return"]')).flatpickr({
                enableTime: false,
                dateFormat: "Y-m-d",
                locale: "vn",
            });

            $("#select_bank_list").select2({
                templateSelection: optionFormatCard,
                templateResult: optionFormatCard,
                minimumResultsForSearch: 0,
                dropdownParent: $("#select_bank")
            });
        }
    };
};

// Initialize everything when DOM is ready
KTUtil.onDOMContentLoaded(function () {
    CustomerList.init();
    CustomerList.initDatatable();
    KTModalCustomersAdd.init();
    FormAddCard().init();
});
