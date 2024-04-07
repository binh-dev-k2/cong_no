"use strict";

var CustomerList = function() {
    var datatable = $("#kt_customers_table");

    var updateToolbar = () => {
        const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
        const checkboxes = document.querySelectorAll(' tbody [type="checkbox"]');
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

        return checkedCount;
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
        function  messageStatus() {
            if (updateToolbar() === 1) {
                var customerName = document.querySelector('tbody [type="checkbox"]:checked')
                    .closest('tr').querySelector('td:nth-child(2)').innerText;
                console.log(customerName);
               return Swal.fire({
                   text: `Bạn có muốn xóa khách hàng ${customerName } không?`,
                   icon: "warning",
                   showCancelButton: true,
                   buttonsStyling: false,
                   confirmButtonText: "Có, xóa!",
                   cancelButtonText: "Không, hủy bỏ",
                   customClass: {
                       confirmButton: "btn fw-bold btn-danger",
                       cancelButton: "btn fw-bold btn-active-light-primary"
                   }});
            }
            else if (updateToolbar() > 1) {
                return Swal.fire({
                    text: "Bạn có muốn xóa tất cả khách hàng đã chọn?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Có, xóa!",
                    cancelButtonText: "Không, hủy bỏ!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }});
            }
        }
        deleteSelectedBtn.addEventListener("click", (function() {
            messageStatus().then((function(result) {
                const headers = {
                    Authorization: `Bearer ${token}`,
                };
                if (result.value) {
                    var list_selected = [];
                    var checkboxed = document.querySelectorAll('tbody [type="checkbox"]');
                    checkboxed.forEach((checkbox => {
                        if (checkbox.checked) {
                            list_selected= [...list_selected, checkbox.closest('tr')
                                .querySelector('.button-edit').getAttribute('data-target')];
                        }
                    }));
                    const deletePromises = list_selected.map((customerPhone) => {
                        const url = delete_customer_route.replace(':phone', customerPhone);
                        return axios.delete(url, { headers: headers });
                    });
                    Promise.all(deletePromises)
                        .then((response) => {
                            var notiMessage = '';
                            if (updateToolbar() === 1){
                                var customerName = document.querySelector('tbody [type="checkbox"]:checked')
                                    .closest('tr').querySelector('td:nth-child(2)').innerText;
                                notiMessage = `Khách hàng ${customerName} đã bị xóa!`;
                            } else {
                                notiMessage = 'Tất cả khách hàng đã chọn đã bị xóa!';

                            }
                            Swal.fire({
                                text: notiMessage,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok!",
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
                                var baseToolbar =  document.querySelector('[data-kt-customer-table-toolbar="base"]');
                                var selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
                                var selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
                                selectedCount.innerHTML = '';
                                baseToolbar.classList.remove("d-none");
                                selectedToolbar.classList.add('d-none');
                            }));
                        })
                } else if (result.dismiss === "cancel") {
                    Swal.fire({
                        text: "Không có khách hàng nào bị xóa.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                        }
                    });
                }
            }));
        }));
    };



    return {
        init: async function() {
            try {


                if (datatable.length)
                    datatable = datatable.DataTable({
                        info: !1,
                        order: [],
                        columnDefs: [{
                            orderable: !1,
                            targets: 0
                        },
                            {
                                orderable: !1,
                                targets: 6
                            }
                        ],
                    });

                const headers = {
                    Authorization: `Bearer ${token}`,
                };

                const response = await axios.get(showAllCustomers, {
                    headers: headers
                });

                if (response.data.success) {
                    const customers = response.data.data;

                    customers.forEach(customer => {
                        const phone = customer.phone;
                        var cardNumber = customer.card_number.split(',');
                        var dateDue = customer.date_due.split(',');
                        var dateReturn = customer.date_return.split(',');
                        var bank = customer.bank_id.split(',');
                        var satus = customer.status;
                        let rowData;

                        // if ()

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
                                    bank[i] = bank[i].replace(/\s/g, '');
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
                                `<table><td><button class="btn dropdown-toggle button-see-more " data-target="${phone}"></button></td>
                                <td><div class="dropdown">
                                  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Nhắc nợ
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                  </div></td>
                                </div>
                                </table>`,
                                `<table class="see-more-button">
                                <tr>
                                    <td><button class="btn btn-warning button-edit" data-target="${phone}">Sửa</button></td>

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
                                `<table><td><button class="btn invisible" data-target="${phone}">1</button></td>
<td><div class="dropdown">
                                  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Nhắc nợ
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                  </div>
                                </div></td></table>`,
                                `<table class="see-more-button">
                                    <tr>
                                <td><button class="btn btn-warning button-edit" data-target="${phone}">Sửa</button></td>
                                <td><button class="btn invisible" data-target="${phone}">1</button></td>
                                </tr>
                        </table>`,

                            ];
                        }
                        datatable.row.add(rowData);

                    })
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
            initDeleteSelected();
            document.querySelector('[data-kt-customer-table-filter="search"]').addEventListener("keyup", (function(event) {
                datatable.search(event.target.value).draw();
            }));
        }
    };

}();

KTUtil.onDOMContentLoaded((function() {
    CustomerList.init();
}));
