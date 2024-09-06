"use strict";


var KTModalCustomersAdd = (function () {
    let btn_submit, btn_cancel, btn_close, formValidate, form, i, btn_add_customer;

    const headers = {
        Authorization: `Bearer ${token}`,
    };

    const optionFormat = function (item) {
        if (!item.id) {
            return item.text;
        }

        let span = document.createElement('span');
        let template = `<img src="${item.bank_logo}" class="h-20px mb-1" />${item.text}`;

        span.innerHTML = template;

        return $(span);
    }

    const initGetBlankCards = function () {
        $("#select_add_card").select2({
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
                headers: headers,
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.card_number,
                                id: item.id,
                                bank_logo: item.bank.logo
                            }
                        })
                    };
                }
            }
        });
    }

    return {
        init: function () {
            i = new bootstrap.Modal(document.querySelector("#kt_modal_add_customer"))
            btn_add_customer = document.querySelector(".btn-add-customer")
            form = document.querySelector("#kt_modal_add_customer_form")
            btn_submit = form.querySelector("#kt_modal_add_customer_submit")
            btn_cancel = form.querySelector("#kt_modal_add_customer_cancel")
            btn_close = form.querySelector("#kt_modal_add_customer_close")

            btn_add_customer.addEventListener('click', function () {
                $("#select_add_card").empty()
                initGetBlankCards()
            })

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
                            phone: {
                                country: "VN",
                                message: "Số điện thoại không hợp lệ",
                            },
                            numberic: {
                                message: "Số điện thoại không hợp lệ",
                            },
                            stringLength: {
                                min: 10,
                                max: 10,
                                message: "Số điện thoại phải đủ 10 số",
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
            })

            btn_cancel.addEventListener("click", function (event) {
                event.preventDefault();
                Swal.fire({
                    text: "Bạn chắc chắn rằng muốn thoát khỏi form này?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Xác nhận!",
                    cancelButtonText: "Quay lại",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light",
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        i.hide();
                        form.reset();
                    }
                });
            });

            btn_close.addEventListener("click", function (event) {
                event.preventDefault();
                Swal.fire({
                    text: "Bạn chắc chắn rằng muốn thoát khỏi form này?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Xác nhận!",
                    cancelButtonText: "Quay lại",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light",
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        i.hide();
                        form.reset();
                    }
                });
            });

            btn_submit.addEventListener("click", function (e) {
                e.preventDefault();
                formValidate && formValidate.validate().then((status) => {
                    if (status === "Valid") {
                        let data = {
                            customer_name: form.querySelector("input[name='name']").value,
                            customer_phone: form.querySelector("input[name='phone']").value,
                            // fee_percent: form.querySelector("input[name='fee_percent']").value,
                            card_ids: $("#select_add_card").select2("val"),
                        };
                        // console.log(data);

                        axios.post(routes.storeCustomer, data, { headers: headers })
                            .then((res) => {
                                if (res.data.code === 0) {
                                    Swal.fire({
                                        text: "Thêm khách hàng thành công",
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        }
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            i.hide();
                                            form.reset();
                                            $("#select_add_card").empty()
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
                    }
                });
            });
        }
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTModalCustomersAdd.init();
});
