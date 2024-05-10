"use strict";


var KTModalCustomersAdd = (function () {
    let btn_submit, btn_cancel, btn_close, formValidate, form, i, btn_card_add, list_card, card_number_find, timeout_card_find;
    const headers = {
        Authorization: `Bearer ${token}`,
    };

    return {
        init: function () {
            i = new bootstrap.Modal(document.querySelector("#kt_modal_add_customer")),
                form = document.querySelector("#kt_modal_add_customer_form"),
                btn_submit = form.querySelector("#kt_modal_add_customer_submit"),
                btn_cancel = form.querySelector("#kt_modal_add_customer_cancel"),
                btn_card_add = form.querySelector("#btn_modal_card_add"),
                btn_close = form.querySelector("#kt_modal_add_customer_close"),
                card_number_find = form.querySelector("input[name='card_number_find']"),

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
                //         .on("core.form.valid", function() {
                //         const listContainer = document.getElementById("list_card_added");
                //         console.log(listContainer.childElementCount);
                //         if (listContainer.childElementCount === 0) {
                //         formValidate.updateFieldStatus("kt_modal_add_customer_billing_info", "Invalid", "notEmpty");
                //     } else {
                //         formValidate.updateFieldStatus("kt_modal_add_customer_billing_info", "Valid", "notEmpty");
                //     }
                // })
                ,

                btn_cancel.addEventListener("click", function (event) {
                    event.preventDefault();
                    Swal.fire({
                        text: "Are you sure you would like to cancel?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, cancel it!",
                        cancelButtonText: "No, return",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light",
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            i.hide();
                            form.reset();
                            list_card = [];
                            document.dispatchEvent(changeListCardEvent);
                        }

                    });
                });

            btn_close.addEventListener("click", function (event) {
                event.preventDefault();
                Swal.fire({
                    text: "Are you sure you would like to cancel?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, cancel it!",
                    cancelButtonText: "No, return",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light",
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        i.hide();
                        form.reset();
                        list_card = [];
                        document.dispatchEvent(changeListCardEvent);
                    }
                });
            });

            card_number_find.addEventListener("input", function () {
                clearTimeout(timeout_card_find)

                timeout_card_find = setTimeout(() => {
                    axios.get(routes.findCard, {
                        params: {
                            card_number,
                        },
                        headers,
                    })
                }, 300)
            })

            btn_card_add.addEventListener("click", function () {
                let card_number = form.querySelector(
                    "input[name='card_number_find']"
                ).value;
                if (card_number.length === 0) {
                    Swal.fire({
                        text: "Bạn chưa điền số tài khoản hoặc số thẻ ngân hàng.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Quay lại ",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                } else {
                    axios.get(routes.findCard, {
                        params: {
                            card_number,
                        },
                        headers,
                    })
                        .then(function (response) {
                            let card = response.data.data.card;
                            let find_result = list_card.find(e => e.card_number === card.card_number);
                            if (find_result === undefined) {
                                list_card.push(card);
                                document.dispatchEvent(changeListCardEvent);
                            } else {
                                Swal.fire({
                                    text: "Thẻ đã được thêm trước đó",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Quay lại ",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                });
                            }
                        })
                        .catch(function (error) {
                            const errorMessage = [
                                "",
                                "Thẻ không tồn tại trong hệ thống, hãy thêm mới",
                                "Thẻ đã được chỉ định cho khách hàng khác",
                            ];
                            const errorCode = error.response.data.data.code;
                            Swal.fire({
                                text: errorMessage[errorCode],
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Quay lại ",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            });
                        });
                }
            }),
                document.addEventListener("changeListCardEvent", function () {
                    $("#list_card_added").html("");
                    for (let index = 0; index < list_card.length; index++) {
                        const element = list_card[index];
                        let card_clone = document.getElementById("template_card").content.cloneNode(true);
                        card_clone.querySelector("img").src = element.bank.logo;
                        card_clone.querySelector(".account_name").textContent = element.account_name;
                        card_clone.querySelector(".card_added_input").value = element.card_number;
                        card_clone.querySelector(".card_number").textContent = element.card_number;
                        card_clone.querySelector(".btn_delete").setAttribute('card_id', element.card_number);
                        card_clone.querySelector(".btn_delete").addEventListener("click", function () {
                            let index = list_card.findIndex(item => item.card_number === element.card_number);
                            if (index !== -1) {
                                list_card.splice(index, 1);
                            }
                            document.dispatchEvent(changeListCardEvent);
                        });
                        $("#list_card_added").append(card_clone);
                    }
                }),
                btn_submit.addEventListener("click", function (e) {
                    e.preventDefault();
                    formValidate && formValidate.validate().then((status) => {
                        if (status === "Valid") {
                            let data = {
                                customer_name: form.querySelector("input[name='name']").value,
                                customer_phone: form.querySelector("input[name='phone']").value,
                                card_added_number: list_card.map((e) => e.card_number),
                            };
                            console.log(list_card, data);

                            axios.post(routes.addCustomer, data, headers)
                                .then((response) =>
                                    Swal.fire({
                                        text: "Thêm khách hàng thành công",
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            i.hide();
                                            form.reset();
                                            list_card = [];
                                            document.dispatchEvent(changeListCardEvent);
                                            window.location.reload();
                                        }

                                    })
                                )
                                .catch((err) => {
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
                        } else {
                            Swal.fire({
                                text: "Tên khách hàng và số điện thoại không được để trống",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Quay lại ",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            });
                        }
                    });
                });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTModalCustomersAdd.init();
});
