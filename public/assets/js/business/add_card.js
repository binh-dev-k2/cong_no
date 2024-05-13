"use strict";
var FormAddCard = (function () {
    var collapse_add_new_card, form, btn_submit_add_new_card, formValidate;
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
                        account_name: {
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
                        bank_code: {
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
                                account_name: form.querySelector(
                                    'input[name="account_name"]'
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
                                bank_code: form.querySelector(
                                    'select[name="bank_code"]'
                                ).value,
                                note: form.querySelector(
                                    'textarea[name="note"]'
                                ).value,
                            };
                            const headers = {
                                Authorization: `Bearer ${token}`,
                            };
                            axios
                                .post(routes.storeCard, data, {
                                    headers: headers,
                                })

                                .then((response) => {
                                    btn_submit_add_new_card.removeAttribute(
                                        "data-kt-indicator"
                                    );
                                    console.log(response);
                                    if (response.status === 200) {
                                        Swal.fire({
                                            text: response.data.message,
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Thành công!",
                                            customClass: {
                                                confirmButton:
                                                    "btn btn-primary",
                                            },
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                form.reset();
                                                $("#collapse_add_new_card").collapse('hide');

                                            }
                                        });
                                    }
                                })
                                .catch((err) => {
                                    btn_submit_add_new_card.removeAttribute(
                                        "data-kt-indicator"
                                    );

                                    if (err.response.status === 422) {
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
                                                confirmButton: "btn btn-primary",
                                            },
                                        });
                                    }
                                });
                        }
                        else {
                            btn_submit_add_new_card.removeAttribute(
                                "data-kt-indicator"
                            );
                            swal.fire({
                                text: "Dữ liệu không hợp lệ, vui lòng kiểm tra lại",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Quay lại",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },

                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    KTUtil.scrollTop();

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
