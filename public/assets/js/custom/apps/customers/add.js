"use strict";
var KTModalCustomersAdd = (function () {
    var btn_submit, btn_cancel, btn_close, formValidate, form, i, btn_card_add;
    return {
        init: function () {
            (i = new bootstrap.Modal(
                document.querySelector("#kt_modal_add_customer")
            )),
                (form = document.querySelector("#kt_modal_add_customer_form")),
                (btn_submit = form.querySelector(
                    "#kt_modal_add_customer_submit"
                )),
                (btn_cancel = form.querySelector(
                    "#kt_modal_add_customer_cancel"
                )),
                (btn_card_add = form.querySelector("#btn_modal_card_add")),
                (btn_close = form.querySelector(
                    "#kt_modal_add_customer_close"
                )),
                (formValidate = FormValidation.formValidation(form, {
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
                })),
                btn_submit.addEventListener("click", function (e) {
                    e.preventDefault();
                        formValidate &&
                            formValidate.validate().then((status) => {
                                if (status === "Valid") {

                                }
                            });
                }),
                btn_cancel.addEventListener("click", function (t) {
                    t.preventDefault(),
                        Swal.fire({
                            text: "Are you sure you would like to cancel?",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, cancel it!",
                            cancelButtonText: "No, return",
                            customClass: {
                                confirmButton: "btn btn-primary",
                                cancelButton: "btn btn-active-light",
                            },
                        }).then(function (t) {
                            t.value
                                ? (form.reset(), i.hide())
                                : "cancel" === t.dismiss &&
                                  Swal.fire({
                                      text: "Your form has not been cancelled!.",
                                      icon: "error",
                                      buttonsStyling: !1,
                                      confirmButtonText: "Ok, got it!",
                                      customClass: {
                                          confirmButton: "btn btn-primary",
                                      },
                                  });
                        });
                }),
                btn_close.addEventListener("click", function (t) {
                    t.preventDefault(),
                        Swal.fire({
                            text: "Are you sure you would like to cancel?",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, cancel it!",
                            cancelButtonText: "No, return",
                            customClass: {
                                confirmButton: "btn btn-primary",
                                cancelButton: "btn btn-active-light",
                            },
                        }).then(function (t) {
                            t.value
                                ? (form.reset(), i.hide())
                                : "cancel" === t.dismiss &&
                                  Swal.fire({
                                      text: "Your form has not been cancelled!.",
                                      icon: "error",
                                      buttonsStyling: !1,
                                      confirmButtonText: "Ok, got it!",
                                      customClass: {
                                          confirmButton: "btn btn-primary",
                                      },
                                  });
                        });
                }),
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
                        const headers = {
                            Authorization: `Bearer ${token}`,
                        };
                        axios
                            .get(find_card_route, {
                                params: {
                                    card_number,
                                },
                                headers,
                            })
                            .then(function (response) {
                                let card = response.data.data.card;
                                let find_result = list_card.find(e => e.card_number === card.card_number);
                                if(find_result === undefined) {
                                    list_card.push(card);
                                    document.dispatchEvent(changeListCardEvent);
                                }else {
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
                                // Xử lý lỗi ở đây
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
                        let card_clone = document
                            .getElementById("template_card")
                            .content.cloneNode(true);
                        card_clone.querySelector("img").src = element.bank.logo;
                        card_clone.querySelector(".account_name").textContent =
                            element.card_name;
                        card_clone.querySelector(".card_added_input").value =
                            element.card_number;
                        card_clone.querySelector(".card_number").textContent =
                            element.card_number;
                        card_clone.querySelector(".btn_delete").setAttribute('card_id', element.card_number);
                        card_clone.querySelector(".btn_delete").addEventListener("click", function() {
                            let list_card_new = list_card.filter((item) => {
                                item.code != element.card_number;
                            })
                            list_card = list_card_new;
                            document.dispatchEvent(changeListCardEvent);
                        });
                        $("#list_card_added").append(card_clone);
                    }
                });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTModalCustomersAdd.init();
});
