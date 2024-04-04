"use strict";
var KTCustomersList = (function () {
    var t,
        e,
        o,
        n,
        c = () => {
            n.querySelectorAll(
                '[data-kt-customer-table-filter="delete_row"]'
            ).forEach((e) => {
                e.addEventListener("click", function (e) {
                    e.preventDefault();
                    const o = e.target.closest("tr"),
                        n = o.querySelectorAll("td")[1].innerText;
                    Swal.fire({
                        text: "Are you sure you want to delete " + n + "?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, delete!",
                        cancelButtonText: "No, cancel",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton:
                                "btn fw-bold btn-active-light-primary",
                        },
                    }).then(function (e) {
                        e.value
                            ? Swal.fire({
                                  text: "You have deleted " + n + "!.",
                                  icon: "success",
                                  buttonsStyling: !1,
                                  confirmButtonText: "Ok, got it!",
                                  customClass: {
                                      confirmButton: "btn fw-bold btn-primary",
                                  },
                              }).then(function () {
                                  t.row($(o)).remove().draw();
                              })
                            : "cancel" === e.dismiss &&
                              Swal.fire({
                                  text: n + " was not deleted.",
                                  icon: "error",
                                  buttonsStyling: !1,
                                  confirmButtonText: "Ok, got it!",
                                  customClass: {
                                      confirmButton: "btn fw-bold btn-primary",
                                  },
                              });
                    });
                });
            });
        },
        r = () => {
            const e = n.querySelectorAll('[type="checkbox"]'),
                o = document.querySelector(
                    '[data-kt-customer-table-select="delete_selected"]'
                );
            e.forEach((t) => {
                t.addEventListener("click", function () {
                    setTimeout(function () {
                        l();
                    }, 50);
                });
            }),
                o.addEventListener("click", function () {
                    Swal.fire({
                        text: "Are you sure you want to delete selected customers?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Yes, delete!",
                        cancelButtonText: "No, cancel",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton:
                                "btn fw-bold btn-active-light-primary",
                        },
                    }).then(function (o) {
                        o.value
                            ? Swal.fire({
                                  text: "You have deleted all selected customers!.",
                                  icon: "success",
                                  buttonsStyling: !1,
                                  confirmButtonText: "Ok, got it!",
                                  customClass: {
                                      confirmButton: "btn fw-bold btn-primary",
                                  },
                              }).then(function () {
                                  e.forEach((e) => {
                                      e.checked &&
                                          t
                                              .row($(e.closest("tbody tr")))
                                              .remove()
                                              .draw();
                                  });
                                  n.querySelectorAll(
                                      '[type="checkbox"]'
                                  )[0].checked = !1;
                              })
                            : "cancel" === o.dismiss &&
                              Swal.fire({
                                  text: "Selected customers was not deleted.",
                                  icon: "error",
                                  buttonsStyling: !1,
                                  confirmButtonText: "Ok, got it!",
                                  customClass: {
                                      confirmButton: "btn fw-bold btn-primary",
                                  },
                              });
                    });
                });
        };
    const l = () => {
        const t = document.querySelector(
                '[data-kt-customer-table-toolbar="base"]'
            ),
            e = document.querySelector(
                '[data-kt-customer-table-toolbar="selected"]'
            ),
            o = document.querySelector(
                '[data-kt-customer-table-select="selected_count"]'
            ),
            c = n.querySelectorAll('tbody [type="checkbox"]');
        let r = !1,
            l = 0;
        c.forEach((t) => {
            t.checked && ((r = !0), l++);
        }),
            r
                ? ((o.innerHTML = l),
                  t.classList.add("d-none"),
                  e.classList.remove("d-none"))
                : (t.classList.remove("d-none"), e.classList.add("d-none"));
    };
    return {
        init: function () {
            (n = document.querySelector("#kt_customers_table")) &&
                (n.querySelectorAll("tbody tr").forEach((t) => {
                    const e = t.querySelectorAll("td"),
                        o = moment(e[5].innerHTML, "DD MMM YYYY, LT").format();
                    e[5].setAttribute("data-order", o);
                }),
                (t = $(n).DataTable({

                    info: !1,
                    order: [],
                    columnDefs: [
                        { orderable: !1, targets: 0 },
                        { orderable: !1, targets: 6 },
                    ],
                })).on("draw", function () {
                    r(), c(), l(), KTMenu.init();
                }),
                r(),
                document
                    .querySelector('[data-kt-customer-table-filter="search"]')
                    .addEventListener("keyup", function (e) {
                        t.search(e.target.value).draw();
                    }),
                (e = $('[data-kt-customer-table-filter="month"]')),
                (o = document.querySelectorAll(
                    '[data-kt-customer-table-filter="payment_type"] [name="payment_type"]'
                )),
                document
                    .querySelector('[data-kt-customer-table-filter="filter"]')
                    .addEventListener("click", function () {
                        const n = e.val();
                        let c = "";
                        o.forEach((t) => {
                            t.checked && (c = t.value), "all" === c && (c = "");
                        });
                        const r = n + " " + c;
                        t.search(r).draw();
                    }),
                c(),
                document
                    .querySelector('[data-kt-customer-table-filter="reset"]')
                    .addEventListener("click", function () {
                        e.val(null).trigger("change"),
                            (o[0].checked = !0),
                            t.search("").draw();
                    }));
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTCustomersList.init();
});
// document.addEventListener('DOMContentLoaded', async function() {
//     try {
//         const headers = {
//             Authorization: `Bearer ${token}`,
//         };
//
//         const response = await axios.get(showAllCustomers, {headers});
//
//         if (response.data.success) {
//             const customers = response.data.data;
//             displayCustomers(customers);
//         } else {
//             console.error('Request failed with status:', response.status);
//         }
//     } catch (error) {
//         console.error('Request failed with error:', error);
//     }
// });
//
// function displayCustomers(customers) {
//     const table = $('#kt_customers_table').DataTable();
//
//     customers.forEach(customer => {
//         const phone = customer.phone;
//         var cardNumber = customer.card_number.split(',');
//         var dateDue = customer.date_due.split(',');
//         var dateReturn = customer.date_return.split(',');
//         var bank = customer.bank_id.split(',');
//         let rowData;
//
//         if (cardNumber.length > 1) {
//             let cardNumberHTML = '';
//             let dateDueHTML = '';
//             let dateReturnHTML = '';
//             for (let i = 1; i < cardNumber.length; i++) {
//                 if (typeof bank[i] === 'undefined') {
//                     cardNumberHTML += `<tr class="hide-num d-none">
//                                     <td><img src="https://api.vietqr.io/img/${bank[0]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
//                                     <td>${cardNumber[i]}</td>
//                                 </tr>`;
//                 } else {
//                     cardNumberHTML += `<tr class="d-none">
//                                     <td><img src="https://api.vietqr.io/img/${bank[i]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
//                                     <td>${cardNumber[i]}</td>
//                                 </tr>`;
//                 }
//                 dateDueHTML += `<tr class="hide-due d-none"><td>${dateDue[i]}</td></tr>`;
//                 dateReturnHTML += `<tr class="hide-return d-none"><td>${dateReturn[i]}</td></tr>`;
//             }
//
//             rowData = [
//                 `<div class="form-check form-check-sm form-check-custom form-check-solid">
//                     <input class="form-check-input" type="checkbox" value="1">
//                 </div>`,
//                 customer.name,
//                 customer.phone,
//                 `<table class="see-more-number">
//                     <tr>
//                         <td><img src="https://api.vietqr.io/img/${bank[0]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
//                         <td>${cardNumber[0]}</td>
//                         <td><div class="btn dropdown-toggle button-see-more" data-toggle="see-more" data-phone="${phone}"></div></td>
//                     </tr>
//                     ${cardNumberHTML}
//                 </table>`,
//                 `<table class="see-more-date-due">
//                     <tr>${dateDue[0]}</tr>
//                     ${dateDueHTML}
//                 </table>`,
//                 `<table class="see-more-date-return">
//                     <tr>${dateReturn[0]}</tr>
//                     ${dateReturnHTML}
//                 </table>`,
//                 `<button class="btn btn-secondary collapse-btn" data-target="${phone}">Collapse</button>`,
//             ];
//         } else {
//             rowData = [
//                 `<div class="form-check form-check-sm form-check-custom form-check-solid">
//                     <input class="form-check-input" type="checkbox" value="1">
//                 </div>`,
//                 customer.name,
//                 customer.phone,
//                 ` <td><img src="https://api.vietqr.io/img/${bank}.png"
//                                    class="rounded-circle h-20px me-2" alt="image"></td>
//                     <td>${customer.card_number}</td>`,
//                 customer.date_due,
//                 customer.date_return,
//                 `<button class="btn btn-secondary collapse-btn" data-target="${phone}">Collapse</button>`,
//             ];
//         }
//
//         table.row.add(rowData).draw();
//     });
//
//
//     $('.button-see-more').on('click', function() {
//         const $row = $(this).closest('table');
//         $row.find('.hide-num').toggleClass('d-none');
//         $row.find('.hide-due').toggleClass('d-none');
//         $row.find('.hide-return').toggleClass('d-none');
//     });
//
//
//
//
// }
//
