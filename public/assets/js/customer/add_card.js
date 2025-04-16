"use strict";

const FormAddCard = () => {
    const modalElement = document.querySelector('#kt_modal_add_card');
    const form = document.querySelector('#kt_modal_add_card_form');
    const btnSubmit = form.querySelector('#submit_add_new_card');
    const btnCancel = document.querySelector('#btn_card_cancel');
    const btnClose = document.querySelector('#modal_add_card_close');
    const addModal = new bootstrap.Modal(modalElement);

    // Format option for select2
    const optionFormat = (item) => {
        if (!item.id) return item.text;
        const span = document.createElement("span");
        const imgUrl = item.element.getAttribute("data-kt-select2-country");
        span.innerHTML = `<img src="${imgUrl}" class="h-20px me-2" style="min-width: 52px" alt="image"/>${item.text}`;
        return $(span);
    };

    // Xử lý reset form khi đóng modal
    const handleModalHidden = () => {
        form.reset();
        $("#select_add_card").empty();
        $('#customer_id').val('');
    };

    // Khởi tạo validate
    let formValidate;
    const initValidation = () => {
        formValidate = FormValidation.formValidation(form, {
            fields: {
                card_number: {
                    validators: {
                        notEmpty: { message: "Số thẻ là bắt buộc" },
                        numeric: { message: "Số thẻ phải là số" },
                        stringLength: { message: "Số thẻ phải có đúng 16 chữ số", min: 16, max: 16 },
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
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({ rowSelector: ".fv-row" }),
            },
        });
    };

    // Xử lý submit
    const handleSubmit = (e) => {
        e.preventDefault();
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
                const headers = {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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

    // Xử lý cancel/close
    const handleCancelOrClose = (event) => {
        event.preventDefault();
        addModal.hide();
        form.reset();
        $("#select_add_card").empty();
        $('#customer_id').val('');
    };
    return {
        init: function () {
            modalElement.addEventListener('hidden.bs.modal', handleModalHidden);
            btnSubmit.addEventListener('click', handleSubmit);
            btnCancel.addEventListener('click', handleCancelOrClose);
            btnClose.addEventListener('click', handleCancelOrClose);
            initValidation();

            // add input datepicker
            $(form.querySelector('[name="date_return"]')).flatpickr({
                enableTime: false,
                dateFormat: "Y-m-d",
                locale: "vn",
            })

            $("#select_bank_list").select2({
                templateSelection: optionFormat,
                templateResult: optionFormat,
                minimumResultsForSearch: 0,
                dropdownParent: $("#select_bank")
            });
        },
        optionFormat: optionFormat
    };
};

KTUtil.onDOMContentLoaded(function () {
    FormAddCard().init();
});
