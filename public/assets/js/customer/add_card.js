// "use strict";
// const FormAddCard = (function () {
//     let form, btn_submit_add_new_card, formValidate;
//     const optionFormat = function (item) {
//         if (!item.id) {
//             return item.text;
//         }
//
//         const span = document.createElement("span");
//         const imgUrl = item.element.getAttribute("data-kt-select2-country");
//         let template = "";
//
//         template +=
//             '<img src="' +
//             imgUrl +
//             '" class="rounded-circle h-20px me-2" alt="image"/>';
//         template += item.text;
//
//         span.innerHTML = template;
//
//         return $(span);
//     };
//
//     return {
//         init: function () {
//             (form = document.querySelector("#modal_add_card_form")),
//                 // (btn_submit_add_new_card = form.querySelector(
//                 //     "#submit_add_new_card"
//                 // )),
//                 (formValidate = FormValidation.formValidation(form, {
//                     fields: {
//                         card_number: {
//                             validators: {
//                                 notEmpty: {
//                                     message: "Số thẻ là bắt buộc",
//                                 },
//                                 numeric: {
//                                     message: "Số thẻ phải là số",
//                                 },
//                                 stringLength: {
//                                     message: "Số thẻ phải có đúng 16 chữ số",
//                                     min: 16,
//                                     max: 16,
//                                 },
//                             },
//                         },
//                         account_name: {
//                             validators: {
//                                 notEmpty: {
//                                     message: "Chủ tài khoản là bắt buộc",
//                                 },
//                             },
//                         },
//                         account_number: {
//                             validators: {
//                                 notEmpty: {
//                                     message: "Số tài khoản là bắt buộc",
//                                 },
//                                 numeric: {
//                                     message: "Số tài khoản phải là số",
//                                 },
//                             },
//                         },
//                         date_due: {
//                             validators: {
//                                 notEmpty: {
//                                     message: "Ngày đáo hạn là bắt buộc",
//                                 },
//                                 date: {
//                                     format: "YYYY-MM-DD",
//                                     message:
//                                         "Ngày đáo hạn phải có định dạng YYYY-MM-DD",
//                                 },
//                             },
//                         },
//                         date_return: {
//                             validators: {
//                                 // notEmpty: {
//                                 //     message: "Ngày trả là bắt buộc",
//                                 // },
//                                 date: {
//                                     format: "YYYY-MM-DD",
//                                     message:
//                                         "Ngày trả phải có định dạng YYYY-MM-DD",
//                                 },
//                             },
//                         },
//                         // login_info: {
//                         //     validators: {
//                         //         notEmpty: {
//                         //             message: "Thông tin đăng nhập là bắt buộc",
//                         //         },
//                         //     },
//                         // },
//                         bank_code: {
//                             validators: {
//                                 notEmpty: {
//                                     message: "ID Ngân hàng là bắt buộc",
//                                 },
//                             },
//                         },
//                         fee_percent: {
//                             validators: {
//                                 notEmpty: {
//                                     message: "Phần trăm phí là bắt buộc",
//                                 },
//                             },
//                         },
//                         // total_money: {
//                         //     validators: {
//                         //         notEmpty: {
//                         //             message: "Tổng số tiền là bắt buộc",
//                         //         },
//                         //     },
//                         // },
//                         // formality: {
//                         //     validators: {
//                         //         notEmpty: {
//                         //             message: "Hình thức là bắt buộc",
//                         //         },
//                         //     },
//                         // },
//                         // pay_extra: {
//                         //     validators: {
//                         //         numeric: {
//                         //             message: "Tiền trả thêm phải là số",
//                         //         },
//                         //     },
//                         // },
//                     },
//                     plugins: {
//                         trigger: new FormValidation.plugins.Trigger(),
//                         bootstrap: new FormValidation.plugins.Bootstrap5({
//                             rowSelector: ".fv-row",
//                             eleInvalidClass: "",
//                             eleValidClass: "",
//                         }),
//                     },
//                 })),
//                 btn_submit_add_new_card.addEventListener("click", function (e) {
//                     btn_submit_add_new_card.setAttribute(
//                         "data-kt-indicator",
//                         "on"
//                     );
//                     e.preventDefault();
//                     formValidate.validate().then((status) => {
//                         if (status === "Valid") {
//                             let data = {
//                                 account_name: form.querySelector(
//                                     'input[name="account_name"]'
//                                 ).value,
//                                 card_number: form.querySelector(
//                                     'input[name="card_number"]'
//                                 ).value,
//                                 account_number: form.querySelector(
//                                     'input[name="account_number"]'
//                                 ).value,
//                                 date_due: form.querySelector(
//                                     'input[name="date_due"]'
//                                 ).value,
//                                 date_return: form.querySelector(
//                                     'input[name="date_return"]'
//                                 ).value,
//                                 login_info: form.querySelector(
//                                     'input[name="login_info"]'
//                                 ).value,
//                                 bank_code: form.querySelector(
//                                     'select[name="bank_code"]'
//                                 ).value,
//                                 fee_percent: form.querySelector(
//                                     'input[name="fee_percent"]'
//                                 ).value,
//                                 // total_money: form.querySelector(
//                                 //     'input[name="total_money"]'
//                                 // ).value,
//                                 // formality: form.querySelector(
//                                 //     'select[name="select_formality"]'
//                                 // ).value,
//                                 // pay_extra: form.querySelector(
//                                 //     'input[name="pay_extra"]'
//                                 // ).value,
//                                 note: form.querySelector(
//                                     'textarea[name="note"]'
//                                 ).value,
//                             };
//                             const headers = {
//                                 Authorization: `Bearer ${token}`,
//                             };
//                             axios
//                                 .post(routes.storeCard, data, {
//                                     headers: headers,
//                                 })
//
//                                 .then((response) => {
//                                     btn_submit_add_new_card.removeAttribute(
//                                         "data-kt-indicator"
//                                     );
//                                     if (response.status === 200) {
//                                         Swal.fire({
//                                             text: response.data.message,
//                                             icon: "success",
//                                             buttonsStyling: !1,
//                                             confirmButtonText: "Thành công!",
//                                             customClass: {
//                                                 confirmButton:
//                                                     "btn btn-primary",
//                                             },
//                                         }).then(function (result) {
//                                             if (result.isConfirmed) {
//                                                 form.reset();
//
//                                             }
//                                         });
//                                     }
//                                 })
//                                 .catch((err) => {
//                                     btn_submit_add_new_card.removeAttribute(
//                                         "data-kt-indicator"
//                                     );
//
//                                     if (err.response.status === 422) {
//                                         let messages = err.response.data.errors;
//                                         let errorMessage = [];
//                                         for (const key in messages) {
//                                             if (
//                                                 Object.hasOwnProperty.call(
//                                                     messages,
//                                                     key
//                                                 )
//                                             ) {
//                                                 const element = messages[key];
//                                                 errorMessage.push(element);
//                                             }
//                                         }
//                                         Swal.fire({
//                                             text: errorMessage.join(", "),
//                                             icon: "error",
//                                             buttonsStyling: !1,
//                                             confirmButtonText: "Quay lại ",
//                                             customClass: {
//                                                 confirmButton:
//                                                     "btn btn-primary",
//                                             },
//                                         });
//                                     } else {
//                                         Swal.fire({
//                                             text: err.message,
//                                             icon: "error",
//                                             buttonsStyling: !1,
//                                             confirmButtonText: "Quay lại ",
//                                             customClass: {
//                                                 confirmButton: "btn btn-primary",
//                                             },
//                                         });
//                                     }
//                                 });
//                         } else {
//                             btn_submit_add_new_card.removeAttribute(
//                                 "data-kt-indicator"
//                             );
//                             swal.fire({
//                                 text: "Dữ liệu không hợp lệ, vui lòng kiểm tra lại",
//                                 icon: "error",
//                                 buttonsStyling: !1,
//                                 confirmButtonText: "Quay lại",
//                                 customClass: {
//                                     confirmButton: "btn btn-primary",
//                                 },
//
//                             }).then(function (result) {
//                                 if (result.isConfirmed) {
//                                     KTUtil.scrollTop();
//
//                                 }
//                             });
//                         }
//
//                     });
//                 });
//             // add input datepicker
//             $(
//                 collapse_add_new_card.querySelector('[name="date_due"]')
//             ).flatpickr({
//                 enableTime: false,
//                 dateFormat: "Y-m-d",
//                 locale: "vn",
//             });
//             // add input datepicker
//             $(
//                 collapse_add_new_card.querySelector('[name="date_return"]')
//             ).flatpickr({
//                 enableTime: false,
//                 dateFormat: "Y-m-d",
//                 locale: "vn",
//             }),
//                 $("#select_bank_list").select2({
//                     templateSelection: optionFormat,
//                     templateResult: optionFormat,
//                     minimumResultsForSearch: Infinity,
//                 });
//         },
//     };
// })();
// KTUtil.onDOMContentLoaded(function () {
//     FormAddCard.init();
// });
