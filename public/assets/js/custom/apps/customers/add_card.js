"use strict";
var FormAddCard = (function () {
    var collapse_add_new_card, i, form, btn_submit_add_new_card, formValidate;
    var optionFormat = function (item) {
        if (!item.id) {
            return item.text;
        }

        var span = document.createElement("span");
        var imgUrl = item.element.getAttribute("data-kt-select2-country");
        var template = "";

        template +=
            '<img src="' +
            imgUrl +
            '" class="rounded-circle h-20px me-2" alt="image"/>';
        template += item.text;

        span.innerHTML = template;

        return $(span);
    };

    return {
        init: function () {
            (i = new bootstrap.Modal(
                document.querySelector("#kt_modal_add_customer")
            )),
                (form = document.querySelector("#kt_modal_add_customer_form")),
                (collapse_add_new_card = form.querySelector(
                    "#collapse_add_new_card"
                )),
                (btn_submit_add_new_card = form.querySelector(
                    "#submit_add_new_card"
                )),
                (formValidate = FormValidation.formValidation(form, {
                    fields: {
                        card_number: {
                            validators: {
                                notEmpty: {
                                    message: "Số thẻ là bắt buộc",
                                },
                                numeric: {
                                    message: "Số thẻ phải là số",
                                },
                                stringLength: {
                                    message: "Số thẻ phải có đúng 16 chữ số",
                                    min: 16,
                                    max: 16,
                                },
                            },
                        },
                        card_name: {
                            validators: {
                                notEmpty: {
                                    message: "Chủ tài khoản là bắt buộc",
                                },
                            },
                        },
                        account_number: {
                            validators: {
                                notEmpty: {
                                    message: "Số tài khoản là bắt buộc",
                                },
                                numeric: {
                                    message: "Số tài khoản phải là số",
                                },
                            },
                        },
                        date_due: {
                            validators: {
                                notEmpty: {
                                    message: "Ngày đáo hạn là bắt buộc",
                                },
                                date: {
                                    format: "YYYY-MM-DD",
                                    message:
                                        "Ngày đáo hạn phải có định dạng YYYY-MM-DD",
                                },
                            },
                        },
                        date_return: {
                            validators: {
                                notEmpty: {
                                    message: "Ngày trả là bắt buộc",
                                },
                                date: {
                                    format: "YYYY-MM-DD",
                                    message:
                                        "Ngày trả phải có định dạng YYYY-MM-DD",
                                },
                            },
                        },
                        login_info: {
                            validators: {
                                notEmpty: {
                                    message: "Thông tin đăng nhập là bắt buộc",
                                },
                            },
                        },
                        bank_id: {
                            validators: {
                                notEmpty: {
                                    message: "ID Ngân hàng là bắt buộc",
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
                })),
                btn_submit_add_new_card.addEventListener("click", function (e) {
                    btn_submit_add_new_card.setAttribute(
                        "data-kt-indicator",
                        "on"
                    );
                    e.preventDefault();
                    formValidate.validate().then((status) => {
                        if (status === "Valid") {
                            let data = {
                                card_name: form.querySelector(
                                    'input[name="card_name"]'
                                ).value,
                                card_number: form.querySelector(
                                    'input[name="card_number"]'
                                ).value,
                                account_number: form.querySelector(
                                    'input[name="account_number"]'
                                ).value,
                                date_due: form.querySelector(
                                    'input[name="date_due"]'
                                ).value,
                                date_return: form.querySelector(
                                    'input[name="date_return"]'
                                ).value,
                                login_info: form.querySelector(
                                    'input[name="login_info"]'
                                ).value,
                                bank_id: form.querySelector(
                                    'select[name="bank_id"]'
                                ).value,
                                note: form.querySelector(
                                    'textarea[name="note"]'
                                ).value,
                            };
                            const headers = {
                                Authorization: `Bearer ${token}`,
                            };
                            axios
                                .post(store_card_route, data, {
                                    headers: headers,
                                })
                                .then((res) => {
                                    btn_submit_add_new_card.removeAttribute(
                                        "data-kt-indicator"
                                    );
                                    
                                })
                                .catch((err) => {
                                    btn_submit_add_new_card.removeAttribute(
                                        "data-kt-indicator"
                                    );
                                    if (err.response.status == 422) {
                                        let messages = err.response.data.errors;
                                        let errorMessage = [];
                                        for (const key in messages) {
                                            if (
                                                Object.hasOwnProperty.call(
                                                    messages,
                                                    key
                                                )
                                            ) {
                                                const element = messages[key];
                                                errorMessage.push(element);
                                            }
                                        }
                                        Swal.fire({
                                            text: errorMessage.join(", "),
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Quay lại ",
                                            customClass: {
                                                confirmButton:
                                                    "btn btn-primary",
                                            },
                                        });
                                    } else {
                                        Swal.fire({
                                            text: err.message,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Quay lại ",
                                            customClass: {
                                                confirmButton:
                                                    "btn btn-primary",
                                            },
                                        });
                                    }
                                });
                        }
                    });
                });
            // add input datepicker
            $(
                collapse_add_new_card.querySelector('[name="date_due"]')
            ).flatpickr({
                enableTime: false,
                dateFormat: "Y-m-d",
                locale: "vn",
            });
            // add input datepicker
            $(
                collapse_add_new_card.querySelector('[name="date_return"]')
            ).flatpickr({
                enableTime: false,
                dateFormat: "Y-m-d",
                locale: "vn",
            }),
                $("#select_bank_list").select2({
                    templateSelection: optionFormat,
                    templateResult: optionFormat,
                    minimumResultsForSearch: Infinity,
                });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    FormAddCard.init();
});
