"use strict";

var CustomerList = function() {
    var datatable, filterMonth, filterPaymentType;

    var initDeleteRow = () => {
        document.querySelectorAll('[data-kt-customer-table-filter="delete_row"]').forEach((deleteBtn => {
            deleteBtn.addEventListener("click", (function(event) {
                event.preventDefault();
                const row = event.target.closest("tr");
                const customerName = row.querySelectorAll("td")[1].innerText;
                Swal.fire({
                    text: "Are you sure you want to delete " + customerName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((function(result) {
                    if (result.value) {
                        Swal.fire({
                            text: "You have deleted " + customerName + "!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        }).then((function() {
                            datatable.row($(row)).remove().draw();
                        }));
                    } else if (result.dismiss === "cancel") {
                        Swal.fire({
                            text: customerName + " was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        });
                    }
                }));
            }));
        }));
    };

    var initDeleteSelected = () => {
        const checkboxes = document.querySelectorAll('[type="checkbox"]');
        const deleteSelectedBtn = document.querySelector('[data-kt-customer-table-select="delete_selected"]');

        checkboxes.forEach((checkbox => {
            checkbox.addEventListener("click", (function() {
                setTimeout((function() {
                    updateToolbar();
                }), 50);
            }));
        }));

        deleteSelectedBtn.addEventListener("click", (function() {
            Swal.fire({
                text: "Are you sure you want to delete selected customers?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then((function(result) {
                if (result.value) {
                    Swal.fire({
                        text: "You have deleted all selected customers!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                        }
                    }).then((function() {
                        checkboxes.forEach((checkbox => {
                            if (checkbox.checked) {
                                datatable.row($(checkbox.closest("tbody tr"))).remove().draw();
                            }
                        }));
                        document.querySelectorAll('[type="checkbox"]')[0].checked = false;
                    }));
                } else if (result.dismiss === "cancel") {
                    Swal.fire({
                        text: "Selected customers were not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                        }
                    });
                }
            }));
        }));
    };

    var updateToolbar = () => {
        const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
        const checkboxes = document.querySelectorAll('tbody [type="checkbox"]');
        let anyChecked = false;
        let checkedCount = 0;

        checkboxes.forEach((checkbox => {
            if (checkbox.checked) {
                anyChecked = true;
                checkedCount++;
            }
        }));

        if (anyChecked) {
            selectedCount.innerHTML = checkedCount;
            baseToolbar.classList.add("d-none");
            selectedToolbar.classList.remove("d-none");
        } else {
            baseToolbar.classList.remove("d-none");
            selectedToolbar.classList.add("d-none");
        }
    };

    return {
        init: async function() {
            try {
                datatable = $("#kt_customers_table");

                if (datatable.length)
                    datatable = datatable.DataTable();

                const headers = {
                    Authorization: `Bearer ${token}`,
                };

                const response = await axios.get(showAllCustomers, {
                    headers
                });

                if (response.data.success) {
                    const customers = response.data.data;

                    customers.forEach(customer => {
                        const phone = customer.phone;
                        var cardNumber = customer.card_number.split(',');
                        var dateDue = customer.date_due.split(',');
                        var dateReturn = customer.date_return.split(',');
                        var bank = customer.bank_id.split(',');
                        let rowData;

                        if (cardNumber.length > 1) {
                            let cardNumberHTML = '';
                            let dateDueHTML = '';
                            let dateReturnHTML = '';
                            for (let i = 1; i < cardNumber.length; i++) {
                                if (typeof bank[i] === 'undefined') {
                                    cardNumberHTML += `<tr class="hide-num d-none">
                                    <td><img src="https://api.vietqr.io/img/${bank[0]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
                                    <td>${cardNumber[i]}</td>
                                </tr>`;
                                } else {
                                    cardNumberHTML += `<tr class="hide-num d-none">
                                    <td><img src="https://api.vietqr.io/img/${bank[i]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
                                    <td>${cardNumber[i]}</td>
                                </tr>`;
                                }
                                dateDueHTML += `<tr class="hide-due d-none"><td>${dateDue[i]}</td></tr>`;
                                dateReturnHTML += `<tr class="hide-return d-none"><td class="align-middle">${dateReturn[i]}</td></tr>`;
                            }

                            rowData = [
                                `<div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1">
                            </div>`,
                                `<table>
                                <tr>
                                    <td>${customer.name}</td>
                                </tr>
                                <tr class="hide_name d-none"><td class="invisible">1</td></tr>
                            </table>`,
                                `<table>
                                <tr>
                                    <td>${phone}</td>
                                </tr>
                                <tr class="hide_phone d-none"><td class="invisible">2</td></tr>
                            </table>`,
                                `<table class="see-more-number">
                                <tr>
                                    <td><img src="https://api.vietqr.io/img/${bank[0]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
                                    <td>${cardNumber[0]}</td>
                                </tr>
                                ${cardNumberHTML}
                            </table>`,
                                `<table class="see-more-date-due">
                                <tr><td>${dateDue[0]}</td></tr>
                                ${dateDueHTML}
                            </table>`,
                                `<table class="see-more-date-return">
                                <tr><td>${dateReturn[0]}</td></tr>
                                ${dateReturnHTML}
                            </table>`,
                                `<table class="see-more-button">
                                <tr>
                                    <td><button class="btn dropdown-toggle button-see-more " data-target="${phone}"></button></td>
                                </tr>
                                <tr class="hide-button d-none">
                                    <td><button class="btn btn-warning button-edit" data-target="${phone}">Xóa</button></td>
                                </tr>
                            </table>`,
                            ];
                        } else {
                            rowData = [
                                `<div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1">
                            </div>`,
                                customer.name,
                                customer.phone,
                                ` <td><img src="https://api.vietqr.io/img/${bank}.png"
                                   class="rounded-circle h-20px me-2" alt="image"></td>
                              <td>${customer.card_number}</td>`,
                                customer.date_due,
                                customer.date_return,
                                ``,
                            ];
                        }
                        datatable.row.add(rowData);

                    });
                    datatable.draw();
                    $('.button-see-more').on('click', function() {
                        const $row = $(this).closest('table').parent().parent();
                        $row.find('.hide-num').toggleClass('d-none');
                        $row.find('.hide-due').toggleClass('d-none');
                        $row.find('.hide-return').toggleClass('d-none');
                        $row.find('.hide_name').toggleClass('d-none');
                        $row.find('.hide_phone').toggleClass('d-none');
                        $row.find('.hide-button').toggleClass('d-none');
                    });
                } else {
                    console.error('Request failed with status:', response.status);
                }
            } catch (error) {
                console.error('Request failed with error:', error);
            }

            filterMonth = $('[data-kt-customer-table-filter="month"]');
            filterPaymentType = document.querySelectorAll('[data-kt-customer-table-filter="payment_type"] [name="payment_type"]');

            document.querySelector('[data-kt-customer-table-filter="search"]').addEventListener("keyup", (function(event) {
                datatable.search(event.target.value).draw();
            }));
        }
    };

}();

KTUtil.onDOMContentLoaded((function() {
    CustomerList.init();
}));
