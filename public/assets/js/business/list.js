"use strict";

/**
 * Business Management System
 * Refactored with modular architecture for better maintainability
 */

// =====================================================
// CORE UTILITIES & CONSTANTS
// =====================================================
const BusinessConstants = {
    SELECTORS: {
        BUSINESS_TABLE: '#business_table',
        BUSINESS_SEARCH: '#business_search',
        BUSINESS_NOTE: '[name="business_note"]',
        MODAL_ADD: '#modal-add',
        MODAL_EDIT: '#modal-edit',
        MODAL_EDIT_SETTING: '#modal-edit-setting'
    },

    CARD_TYPES: {
        VISA: { prefixes: ['4', '6', '7'], field: 'visa_fee_percent' },
        MASTERCARD: { prefixes: ['50', '51', '52', '53', '54', '55'], field: 'master_fee_percent' },
        JCB: { prefixes: ['3'], length: 16, field: 'jcb_fee_percent' },
        AMEX: { prefixes: ['3'], length: 15, field: 'amex_fee_percent' },
        NAPAS: { prefixes: ['9'], field: 'napas_fee_percent' }
    },

    API_DELAY: 500,
    MACHINE_SELECT_DELAY: 100
};

const BusinessUtils = {
    formatDate: (time) => {
        const dateTime = new Date(time);
        const year = dateTime.getFullYear();
        const month = String(dateTime.getMonth() + 1).padStart(2, "0");
        const day = String(dateTime.getDate()).padStart(2, "0");
        const hour = String(dateTime.getHours()).padStart(2, "0");
        const minute = String(dateTime.getMinutes()).padStart(2, "0");
        return `${day}-${month}-${year} ${hour}:${minute}`;
    },

    formatNumber: (number) => {
        const str = number.toString();
        const formattedStr = str.replace(/(.{4})/g, '$1 ');
        return formattedStr.trim();
    },

    formatMoney: (amount) => {
        return amount?.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })
            .replaceAll('.', ',').slice(0, -1) ?? 0;
    },

    createPhoneLink: (phone) => {
        const cleanPhone = phone.startsWith('@') ? phone.substring(1) : phone;
        const url = phone.startsWith('@') ? `https://t.me/${cleanPhone}` : `https://zalo.me/${cleanPhone}`;
        return { cleanPhone, url };
    }
};

// =====================================================
// NOTIFICATION MANAGER
// =====================================================
const NotificationManager = {
    show: (text, type = 'success', showCancelButton = false) => {
        return Swal.fire({
            text: text,
            icon: type,
            buttonsStyling: false,
            showCancelButton: showCancelButton,
            confirmButtonText: "Xác nhận",
            cancelButtonText: "Đóng",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-light"
            }
        });
    },

    success: (text) => NotificationManager.show(text, 'success'),
    error: (text) => NotificationManager.show(text, 'error'),
    warning: (text, showCancel = true) => NotificationManager.show(text, 'warning', showCancel)
};

// =====================================================
// API MANAGER
// =====================================================
const BusinessAPI = {
    headers: { Authorization: token },

    request: async (url, data = {}, method = 'POST') => {
        try {
            const response = await axios[method.toLowerCase()](url, data, {
                headers: BusinessAPI.headers
            });
            return response;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },

    updateNote: (business_note) =>
        BusinessAPI.request(routes.businessUpdateNote, { business_note }),

    findCard: (search) =>
        BusinessAPI.request(routes.cardFind, { search }),

    store: (data) =>
        BusinessAPI.request(routes.businessStore, data),

    update: (data) =>
        BusinessAPI.request(routes.businessUpdate, data),

    complete: (id) =>
        BusinessAPI.request(routes.businessComplete, { id }),

    delete: (id) =>
        BusinessAPI.request(routes.businessDelete, { id }),

    updatePayExtra: (id, pay_extra) =>
        BusinessAPI.request(routes.businessUpdatePayExtra, { id, pay_extra }),

    updateBusinessMoney: (data) =>
        BusinessAPI.request(routes.businessUpdateBusinessMoney, data),

    getEditSetting: () =>
        BusinessAPI.request(routes.businessEditSetting, {}, 'GET')
};

// =====================================================
// CARD TYPE DETECTOR
// =====================================================
const CardTypeDetector = {
    detect: (cardNumber) => {
        if (!cardNumber || cardNumber.length < 1) {
            return { type: null, field: null };
        }

        const firstNumber = cardNumber.substring(0, 1);
        const firstTwoNumbers = cardNumber.substring(0, 2);
        const cardLength = cardNumber.length;

        // Check VISA (4, 6, 7)
        if (['4', '6', '7'].includes(firstNumber)) {
            return { type: 'VISA', field: 'visa_fee_percent' };
        }

        // Check MASTERCARD (50-55)
        if (firstNumber === '5' && firstTwoNumbers >= '50' && firstTwoNumbers <= '55') {
            return { type: 'MASTERCARD', field: 'master_fee_percent' };
        }

        // Check JCB/AMEX (both start with 3)
        if (firstNumber === '3') {
            if (cardLength === 16) {
                return { type: 'JCB', field: 'jcb_fee_percent' };
            } else {
                return { type: 'AMEX', field: 'amex_fee_percent' };
            }
        }

        // Check NAPAS (9)
        if (firstNumber === '9') {
            return { type: 'NAPAS', field: 'napas_fee_percent' };
        }

        return { type: null, field: null };
    }
};

// =====================================================
// MACHINE FILTER MANAGER
// =====================================================
const MachineFilterManager = {
    filterByCardType: (cardNumber) => {
        const { type: cardType, field: feeField } = CardTypeDetector.detect(cardNumber);

        if (!cardType || !feeField) {
            return { cardType: null, machines: [], message: '' };
        }

        const filteredMachines = allMachines.filter(machine => machine[feeField] > 0);

        let message = `Đã xác định thẻ <strong>${cardType}</strong>. Chỉ hiển thị máy hỗ trợ loại thẻ này.`;

        if (filteredMachines.length === 0) {
            message = `<span class="text-danger">Không tìm thấy máy nào hỗ trợ thẻ <strong>${cardType}</strong>!</span>`;
        }

        return { cardType, machines: filteredMachines, message, feeField };
    },

    renderMachineOptions: ($select, machines, currentValue = null) => {
        // Clear existing options except first
        $select.find('option:not(:first)').remove();

        // Add new options
        machines.forEach(machine => {
            const option = new Option(
                `${machine.code} - ${machine.name}`,
                machine.id,
                false,
                machine.id == currentValue
            );

            // Add data attributes for fee percentages
            $(option).data({
                'visa': machine.visa_fee_percent,
                'master': machine.master_fee_percent,
                'jcb': machine.jcb_fee_percent,
                'amex': machine.amex_fee_percent,
                'napas': machine.napas_fee_percent
            });

            $select.append(option);
        });

        // Reset selection if current value not found
        if (currentValue && !machines.some(m => m.id == currentValue)) {
            $select.val('');
        }
    }
};

// =====================================================
// MODAL MANAGER
// =====================================================
const ModalManager = {
    init: () => {
        ModalManager.initAddModal();
        ModalManager.initEditModal();
        ModalManager.setupSharedEvents();
    },

    initAddModal: () => {
        const $modal = $(BusinessConstants.SELECTORS.MODAL_ADD);
        ModalManager.populateSelects($modal);
        ModalManager.bindModalEvents($modal, 'add');
    },

    initEditModal: () => {
        const $modal = $(BusinessConstants.SELECTORS.MODAL_EDIT);
        ModalManager.bindModalEvents($modal, 'edit');
    },

    populateSelects: ($modal, modalType = 'add') => {
        // Populate collaborators
        const $collaboratorSelect = $modal.find('select[name="collaborator_id"]');
        $collaboratorSelect.find('option:not(:first)').remove();

        if (typeof collaborators !== 'undefined') {
            collaborators.forEach(collaborator => {
                $collaboratorSelect.append(new Option(collaborator.name, collaborator.id));
            });
        }

        // Populate business settings (only for add modal)
        if (modalType === 'add' && typeof businessMoneys !== 'undefined') {
            const $container = $modal.find('#business-settings-container');
            $container.empty();

            if (businessMoneys.MONEY) {
                $container.append('<span class="text-muted fw-bold mb-2">Theo khoảng</span>');
                Object.keys(businessMoneys.MONEY).forEach(key => {
                    $container.append(`
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" value="${key}"
                                   data-type="MONEY" name="business_setting_key">
                            <label class="form-check-label">${key}</label>
                        </div>
                    `);
                });
            }

            if (businessMoneys.PERCENT) {
                $container.append('<span class="text-muted fw-bold mb-2">Theo %</span>');
                Object.keys(businessMoneys.PERCENT).forEach(key => {
                    $container.append(`
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" value="${key}"
                                   data-type="PERCENT" name="business_setting_key">
                            <label class="form-check-label">${key}</label>
                        </div>
                    `);
                });
            }
        }
    },

    bindModalEvents: ($modal, modalType) => {
        // Remove existing event listeners
        $modal.off('hide.bs.modal submit');
        $modal.find('input[name="card_number"]').off('keyup');
        $modal.off('click', '.search-results li');
        $modal.off('change', 'select[name="machine_id"]');

        // Modal close event
        $modal.on('hide.bs.modal', () => {
            $modal.find('form')[0].reset();
            $modal.find('#machine-info-hint').text('');
            $modal.find('.search-results').hide();
        });

        // Card search
        ModalManager.bindCardSearch($modal, modalType);

        // Card selection
        ModalManager.bindCardSelection($modal);

        // Machine change
        ModalManager.bindMachineChange($modal);

        // Form submission
        ModalManager.bindFormSubmit($modal, modalType);
    },

    bindCardSearch: ($modal, modalType) => {
        let searchTimeout = null;
        const $results = $modal.find('.search-results');
        const $cardInput = $modal.find('input[name="card_number"]');

        $cardInput.on('keyup', function() {
            clearTimeout(searchTimeout);
            const cardNumber = $(this).val();

            // Filter machines based on card type
            ModalManager.filterMachines($modal, cardNumber);

            // Search in database (only for add modal)
            searchTimeout = setTimeout(async () => {
                try {
                    const response = await BusinessAPI.findCard(cardNumber);
                    ModalManager.renderSearchResults($results, response.data?.data || []);
                } catch (error) {
                    console.error('Card search error:', error);
                    $results.hide();
                }
            }, BusinessConstants.API_DELAY);
        });
    },

    bindCardSelection: ($modal) => {
        $modal.on('click', '.search-results li', function() {
            const data = $(this).data();

            $modal.find('input[name="card_number"]').val(data.card_number);
            $modal.find('input[name="account_name"]').val(data.account_name ?? '');
            $modal.find('input[name="name"]').val(data.customer?.name ?? '');
            $modal.find('input[name="phone"]').val(data.customer?.phone ?? '');
            $modal.find('input[name="fee_percent"]').val(data.fee_percent ?? '');
            $modal.find('.search-results').hide();

            // Filter machines after card selection
            ModalManager.filterMachines($modal, data.card_number);
        });
    },

    bindMachineChange: ($modal) => {
        $modal.on('change', 'select[name="machine_id"]', function() {
            const selectedOption = $(this).find('option:selected');
            const cardNumber = $modal.find('input[name="card_number"]').val();

            if (!cardNumber || !selectedOption.val()) return;

            const { field: feeField } = CardTypeDetector.detect(cardNumber);
            if (!feeField) return;

            // Get fee percentage from selected machine
            const dataField = feeField.replace('_fee_percent', '');
            const feePercent = selectedOption.data(dataField);

            if (feePercent > 0) {
                $modal.find('input[name="fee_percent"]').val(feePercent);
            }
        });
    },

    bindFormSubmit: ($modal, modalType) => {
        $modal.on('submit', 'form', async function(e) {
            e.preventDefault();
            const $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.attr('data-kt-indicator', 'on');

            try {
                const formData = ModalManager.getFormData($modal, modalType);
                const response = modalType === 'add' ?
                    await BusinessAPI.store(formData) :
                    await BusinessAPI.update(formData);

                if (response.data.code === 0) {
                    $modal.modal('hide');
                    BusinessDataTable.refresh();
                    NotificationManager.success('Lưu thành công!');
                } else {
                    NotificationManager.error(response.data?.data[0] ?? "Có lỗi gì đó xảy ra!");
                }
            } catch (error) {
                console.error('Form submission error:', error);
                NotificationManager.error(error.message);
            } finally {
                $submitBtn.attr('data-kt-indicator', 'off');
            }
        });
    },

    getFormData: ($modal, modalType) => {
        const data = {
            card_number: $modal.find('input[name="card_number"]').val(),
            account_name: $modal.find('input[name="account_name"]').val(),
            name: $modal.find('input[name="name"]').val(),
            phone: $modal.find('input[name="phone"]').val(),
            fee_percent: parseFloat($modal.find('input[name="fee_percent"]').val()),
            formality: $modal.find('input[name="formality"]:checked').val(),
            machine_id: $modal.find('select[name="machine_id"]').val(),
            collaborator_id: $modal.find('select[name="collaborator_id"]').val(),
            total_money: parseInt($modal.find('input[name="total_money"]').val().replace(/[.,]/g, ''), 10)
        };

        if (modalType === 'add') {
            data.business_setting_key = $modal.find('input[name="business_setting_key"]:checked').val();
            data.business_setting_type = $modal.find('input[name="business_setting_key"]:checked').data('type');
        } else {
            data.id = $modal.find('input[name="id"]').val();
        }

        return data;
    },

    filterMachines: ($modal, cardNumber) => {
        const { cardType, machines, message } = MachineFilterManager.filterByCardType(cardNumber);
        const $hint = $modal.find('#machine-info-hint');
        const $machineSelect = $modal.find('select[name="machine_id"]');

        $hint.html(message);
        MachineFilterManager.renderMachineOptions($machineSelect, machines);
    },

    renderSearchResults: ($results, cards) => {
        $results.empty();

        cards.forEach(card => {
            const image = `<img src="${card.bank.logo}" class="h-20px mb-1" style="min-width: 52px" alt="image"/>`;
            const text = `${card.card_number} ${card.customer ? `- ${card.customer.name} - ${card.customer.phone}` : ''}`;
            const $li = $('<li>').html(image + text).addClass('p-3').data(card);
            $results.append($li);
        });

        $results.toggle(cards.length > 0);
    },

    setupSharedEvents: () => {
        // Money input formatting
        $(document).on('input', 'input[data-type="money"]', function () {
            const value = $(this).val().replace(/[^0-9.]/g, '');
            if (value === '') {
                $(this).val('');
            } else {
                const formatter = new Intl.NumberFormat('en-US', {
                    style: 'decimal',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                });
                $(this).val(formatter.format(parseInt(value)));
            }
        });
    }
};

// =====================================================
// BUSINESS DATA TABLE
// =====================================================
const BusinessDataTable = {
    instance: null,
    searchTimeout: null,
    noteTimeout: null,
    prevPhone: null,

    init: async () => {
        await BusinessDataTable.initTable();
        BusinessDataTable.bindEvents();
        BusinessDataTable.initNoteHandler();
    },

    initTable: async () => {
        BusinessDataTable.instance = $(BusinessConstants.SELECTORS.BUSINESS_TABLE).DataTable({
            lengthMenu: [10, 20, 50, 100],
            pageLength: 50,
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: routes.datatable,
                type: "POST",
                beforeSend: (request) => {
                    request.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                },
                data: (d) => {
                    d.search = $(BusinessConstants.SELECTORS.BUSINESS_SEARCH).val();
                }
            },
            columnDefs: BusinessDataTable.getColumnDefinitions()
        });

        // Re-init functions on table draw
        BusinessDataTable.instance.on('draw', () => {
            BusinessDataTable.initRowActions();
            KTMenu.createInstances();
            $('.paginate_button a').on('click', () => {
                BusinessDataTable.prevPhone = null;
            });
        });
    },

    getColumnDefinitions: () => [
        // Date column
        {
            targets: 0,
            data: 'created_at',
            orderable: false,
            className: 'text-center',
            render: (data) => `<span class="text-nowrap">${BusinessUtils.formatDate(data)}</span>`
        },
        // Customer info column
        {
            targets: 1,
            data: null,
            orderable: false,
            className: 'text-center min-w-150px',
            render: BusinessDataTable.renderCustomerInfo
        },
        // Card info column
        {
            targets: 2,
            data: null,
            orderable: false,
            className: 'text-center min-w-175px',
            render: BusinessDataTable.renderCardInfo
        },
        // Fee percent column
        {
            targets: 3,
            data: 'fee_percent',
            orderable: false,
            className: 'text-center',
            render: (data) => `<span>${data}</span>`
        },
        // Total money column
        {
            targets: 4,
            data: 'total_money',
            orderable: false,
            render: BusinessDataTable.renderTotalMoney
        },
        // Formality column
        {
            targets: 5,
            data: 'formality',
            orderable: false,
            className: 'text-center',
            render: (data) => `<span class="badge badge-${data === 'R' ? 'warning' : 'primary'}">${data}</span>`
        },
        // Fee column
        {
            targets: 6,
            data: null,
            orderable: false,
            render: (data, type, row) => `<span>${BusinessUtils.formatMoney(row?.fee)}</span>`
        },
        // Business money columns (7-12)
        ...Array.from({length: 6}, (_, i) => ({
            targets: 7 + i,
            data: `money.${i}`,
            orderable: false,
            render: (data) => BusinessDataTable.renderBusinessMoneyElement(data, i)
        })),
        // Pay extra column
        {
            targets: 13,
            data: 'pay_extra',
            orderable: false,
            render: BusinessDataTable.renderPayExtra
        },
        // Actions column
        {
            targets: -1,
            data: null,
            orderable: false,
            className: 'text-center min-w-150px',
            render: BusinessDataTable.renderActions
        }
    ],

    renderCustomerInfo: (data, type, row) => {
        if (type === 'display') {
            if (row.account_name) {
                if (row.phone) {
                    const { cleanPhone, url } = BusinessUtils.createPhoneLink(row.phone);
                    return `<div>${row.account_name}</div><a href="${url}" target="_blank">${cleanPhone}</a>`;
                }
                return `<div>${row.account_name}</div>`;
            }

            if (row.phone !== BusinessDataTable.prevPhone) {
                BusinessDataTable.prevPhone = row.phone;
                const { cleanPhone, url } = BusinessUtils.createPhoneLink(row.phone);
                return `
                    <div class="d-flex flex-column align-items-center">
                        <div class="fw-bold text-dark mb-1">${row.name}</div>
                        <a href="${url}" target="_blank" class="text-primary text-decoration-none hover-scale">
                            ${cleanPhone}
                        </a>
                    </div>
                `;
            }
        }
        return `<div></div>`;
    },

    renderCardInfo: (data, type, row) => {
        return `
            <div class="d-flex flex-column align-items-center">
                <img src="${row.bank.logo}" loading="lazy" class="h-40px" alt="${row.bank.code}">
                <span>${BusinessUtils.formatNumber(row.card_number)}</span>
                ${row?.card?.account_number ? BusinessUtils.formatNumber('STK: ' + row.card.account_number) : ''}
                ${row?.card?.date_due ? `<span class="badge badge-primary">Ngày đến hạn: ${row.card.date_due}</span>` : ''}
                ${row?.machine ? `<span class="badge badge-success mt-1">Máy: ${row.machine.name}</span>` : ''}
                ${row?.collaborator ? `<span class="badge badge-info mt-1">CTV: ${row.collaborator.name}</span>` : ''}
            </div>
        `;
    },

    renderTotalMoney: (data, type, row) => {
        return `
            <div class="d-flex flex-column align-items-center">
                <span class="fw-bold ${row.is_paid ? 'text-success' : 'text-danger'}">
                    ${BusinessUtils.formatMoney(data)}
                </span>
                <small class="text-muted">${row.is_paid ? 'Tiền đã về' : 'Tiền chưa về'}</small>
            </div>
        `;
    },

    renderBusinessMoneyElement: (data, id) => {
        if (!data) {
            return `
                <div class="d-flex align-items-center justify-content-between container-business-money">
                    <button class="btn btn-warning btn-edit-business-money p-2" data-id="${id}">Sửa</button>
                </div>
            `;
        }

        return `
            <div class="d-flex align-items-center justify-content-between container-business-money">
                <div class="d-flex align-items-center me-2 min-h-40px bg-secondary rounded-2">
                    <span class="me-2 min-h-40px text-nowrap p-2 rounded ${data.is_money_checked ? 'bg-info text-white' : ''}"
                          style="display: flex; align-items: center; justify-content: center;">
                        ${BusinessUtils.formatMoney(data?.money)}
                    </span>
                    <span class="me-2"> - </span>
                    <span class="min-w-50px text-nowrap min-h-40px p-2 rounded ${data.is_note_checked ? 'bg-info text-white text-truncate' : ''}"
                          style="max-width: 125px; display: flex; align-items: center; justify-content: center;">
                        ${data?.note ?? ''}
                    </span>
                </div>
                <button class="btn btn-warning btn-edit-business-money p-2" data-id="${id}">Sửa</button>
            </div>
        `;
    },

    renderPayExtra: (data) => {
        return `
            <div class="d-flex align-items-center justify-content-between container-pay-extra">
                <span class="me-2">${BusinessUtils.formatMoney(data)}</span>
                -
                <button class="btn btn-warning btn-edit-pay-extra p-2">Sửa</button>
            </div>
        `;
    },

    renderActions: () => {
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
                    <a href="javascript:void(0);" class="menu-link px-3 btn-edit" data-bs-toggle="modal" data-bs-target="#modal-edit">Sửa</a>
                </div>
                <div class="menu-item px-3">
                    <a href="javascript:void(0);" class="menu-link px-3 btn-delete">Xóa</a>
                </div>
                <div class="menu-item px-3">
                    <a href="javascript:void(0);" class="menu-link px-3 btn-complete">Hoàn thành</a>
                </div>
            </div>
        `;
    },

    bindEvents: () => {
        // Search functionality
        $(BusinessConstants.SELECTORS.BUSINESS_SEARCH).on("keyup", () => {
            clearTimeout(BusinessDataTable.searchTimeout);
            BusinessDataTable.searchTimeout = setTimeout(() => {
                BusinessDataTable.prevPhone = null;
                BusinessDataTable.refresh();
            }, BusinessConstants.API_DELAY);
        });

        // Edit setting button
        $('.btn-edit-setting').on('click', async () => {
            try {
                const response = await BusinessAPI.getEditSetting();
                if (response.status === 200) {
                    $('#modal-edit-setting .modal-dialog').html(response.data);
                    $('#modal-edit-setting').modal('show');
                } else {
                    NotificationManager.error("Có lỗi xảy ra...");
                }
            } catch (error) {
                console.error('Edit setting error:', error);
                NotificationManager.error(error.message);
            }
        });
    },

    initNoteHandler: () => {
        $(BusinessConstants.SELECTORS.BUSINESS_NOTE).on('keyup', () => {
            clearTimeout(BusinessDataTable.noteTimeout);
            BusinessDataTable.noteTimeout = setTimeout(async () => {
                const value = $(BusinessConstants.SELECTORS.BUSINESS_NOTE).val();
                try {
                    await BusinessAPI.updateNote(value);
                } catch (error) {
                    console.error('Note update error:', error);
                }
            }, BusinessConstants.API_DELAY);
        });
    },

    initRowActions: () => {
        BusinessDataTable.initComplete();
        BusinessDataTable.initDelete();
        BusinessDataTable.initEdit();
        BusinessDataTable.initEditPayExtra();
        BusinessDataTable.initEditBusinessMoney();
        BusinessDataTable.initActionMenus();
    },

    initComplete: () => {
        $('.btn-complete').off('click').on('click', async function() {
            const row = $(this).closest('tr');
            const data = BusinessDataTable.instance.row(row).data();

            const result = await NotificationManager.warning('Hoàn thành thẻ này?');
            if (result.isConfirmed) {
                try {
                    const response = await BusinessAPI.complete(data.id);
                    if (response.data.code === 0) {
                        await NotificationManager.success('Hoàn thành!');
                        BusinessDataTable.prevPhone = null;
                        BusinessDataTable.refresh();
                    } else {
                        NotificationManager.error(response.data.data.join(", "));
                    }
                } catch (error) {
                    console.error('Complete error:', error);
                    NotificationManager.error(error.message);
                }
            }
        });
    },

    initDelete: () => {
        $('.btn-delete').off('click').on('click', async function() {
            const row = $(this).closest('tr');
            const data = BusinessDataTable.instance.row(row).data();

            const result = await NotificationManager.warning('Bạn có chắc muốn xóa nghiệp vụ này?');
            if (result.isConfirmed) {
                try {
                    const response = await BusinessAPI.delete(data.id);
                    if (response.data.code === 0) {
                        BusinessDataTable.prevPhone = null;
                        BusinessDataTable.refresh();
                    } else {
                        NotificationManager.error('Có lỗi xảy ra, vui lòng thử lại sau!');
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    NotificationManager.error(error.message);
                }
            }
        });
    },

    initEdit: () => {
        $('.btn-edit').off('click').on('click', function() {
            const row = $(this).closest('tr');
            const data = BusinessDataTable.instance.row(row).data();
            const $modal = $(BusinessConstants.SELECTORS.MODAL_EDIT);

            // Set form values
            $modal.find('input[name="id"]').val(data.id);
            $modal.find('input[name="card_number"]').val(data.card_number);
            $modal.find('input[name="account_name"]').val(data.account_name);
            $modal.find('input[name="name"]').val(data.name);
            $modal.find('input[name="phone"]').val(data.phone);
            $modal.find('input[name="fee_percent"]').val(data.fee_percent);
            $modal.find('input[name="total_money"]').val(data.total_money);

            if (data.formality) {
                $modal.find(`input[name="formality"][value="${data.formality}"]`).prop('checked', true);
            }

            // Populate and set selects
            ModalManager.populateSelects($modal, 'edit');
            $modal.find('select[name="collaborator_id"]').val(data.collaborator_id);

            // Filter machines and set value
            ModalManager.filterMachines($modal, data.card_number);
            setTimeout(() => {
                $modal.find('select[name="machine_id"]').val(data.machine_id);
            }, BusinessConstants.MACHINE_SELECT_DELAY);
        });
    },

    initEditPayExtra: () => {
        $('.btn-edit-pay-extra').off('click').on('click', function() {
            const row = $(this).closest('tr');
            const data = BusinessDataTable.instance.row(row).data();
            const td = $(this).closest('td');

            td.find('.container-pay-extra').addClass('d-none');
            td.append(`
                <div class="d-flex container-edit-pay-extra">
                    <input type="number" value="${data.pay_extra ?? 0}" class="form-control" style="min-width: 150px; max-width:200px" min="0"/>
                    <button class="btn btn-light btn-close-pay-extra px-3 py-2">Đóng</button>
                    <button class="btn btn-success btn-save-pay-extra px-3 py-2">Lưu</button>
                </div>
            `);

            td.find('.btn-close-pay-extra').on('click', () => {
                BusinessDataTable.refresh();
            });

            td.find('.btn-save-pay-extra').on('click', async () => {
                const value = td.find('.container-edit-pay-extra input').val();
                try {
                    const response = await BusinessAPI.updatePayExtra(data.id, value);
                    if (response.data.code === 0) {
                        await NotificationManager.success('Lưu thành công!');
                        BusinessDataTable.prevPhone = null;
                        BusinessDataTable.refresh();
                    } else {
                        NotificationManager.error(response.data.data.join(", "));
                    }
                } catch (error) {
                    console.error('Update pay extra error:', error);
                    NotificationManager.error(error.message);
                }
            });
        });
    },

    initEditBusinessMoney: () => {
        $('.btn-edit-business-money').off('click').on('click', function() {
            const dataId = $(this).data('id');
            const row = $(this).closest('tr');
            const data = BusinessDataTable.instance.row(row).data();
            const businessMoney = data.money[dataId] ?? null;
            const td = $(this).closest('td');

            td.find('.container-business-money').addClass('d-none');
            td.append(`
                <div class="d-flex align-items-center container-edit-business-money">
                    <input type="text" data-type="money" value="${businessMoney?.money ?? 0}" class="form-control me-2" style="min-width: 150px; max-width:200px" min="0" step="any"/>
                    <input type="checkbox" data-type="is_money_checked" class="form-check-input me-2 border-primary" ${businessMoney?.is_money_checked ? "checked" : ""}/>
                    <input type="text" data-type="note" value="${businessMoney?.note ?? ''}" class="form-control me-2" style="min-width: 150px; max-width:200px" placeholder="Mã chuẩn chi"/>
                    <input type="checkbox" data-type="is_note_checked" class="form-check-input me-2 border-primary" ${businessMoney?.is_note_checked ? "checked" : ""}/>
                    <div class="position-relative">
                        <i class="fas fa-info-circle text-info cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Tích vào ô trống bên cạnh để tính là đã hoàn thành mã này."></i>
                    </div>
                    <button class="btn btn-light btn-close-business-money px-3 py-2">Đóng</button>
                    <button class="btn btn-success btn-save-business-money px-3 py-2" data-business-id="${data.id}">Lưu</button>
                </div>
            `);

            td.find('.btn-close-business-money').on('click', () => {
                td.find('.container-edit-business-money').remove();
                td.find('.container-business-money').removeClass('d-none');
            });

            td.find('.btn-save-business-money').on('click', async () => {
                const money = td.find('input[data-type="money"]').val().replace(/,/g, '');
                const isMoneyChecked = td.find('input[data-type="is_money_checked"]').prop('checked');
                const note = td.find('input[data-type="note"]').val();
                const isNoteChecked = td.find('input[data-type="is_note_checked"]').prop('checked');
                const businessId = td.find('.btn-save-business-money').data('business-id');

                const requestData = {
                    id: businessMoney?.id ?? null,
                    money: parseInt(money),
                    is_money_checked: isMoneyChecked,
                    note: note,
                    is_note_checked: isNoteChecked,
                    business_id: businessId
                };

                try {
                    const response = await BusinessAPI.updateBusinessMoney(requestData);
                    if (response.data.code === 0) {
                        await NotificationManager.success('Lưu thành công!');
                        BusinessDataTable.prevPhone = null;
                        BusinessDataTable.refresh();
                    } else {
                        NotificationManager.error(response.data.data.join(", "));
                    }
                } catch (error) {
                    console.error('Update business money error:', error);
                    NotificationManager.error(error.message);
                }
            });
        });
    },

    initActionMenus: () => {
        $('[data-kt-menu-trigger="click"]').off('click').on('click', function() {
            $('[data-kt-menu-trigger="click"]').each(function() {
                $(this).closest('td').css('z-index', 0);
            });
            $(this).closest('td').css('z-index', 99);
        });
    },

    refresh: () => {
        if (BusinessDataTable.instance) {
            BusinessDataTable.instance.draw();
        }
    }
};

// =====================================================
// MAIN APPLICATION
// =====================================================
const BusinessApp = {
    init: async () => {
        try {
            await BusinessDataTable.init();
            ModalManager.init();
            console.log('Business Management System initialized successfully');
        } catch (error) {
            console.error('Failed to initialize Business Management System:', error);
            NotificationManager.error('Có lỗi xảy ra khi khởi tạo hệ thống!');
        }
    }
};

// =====================================================
// APPLICATION BOOTSTRAP
// =====================================================
KTUtil.onDOMContentLoaded(() => {
    BusinessApp.init();
});
