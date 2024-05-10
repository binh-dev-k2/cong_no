"use strict";

var CustomerList = function () {
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
            checkbox.addEventListener("click", (function () {
                setTimeout((function () {
                    updateToolbar();
                }), 50);
            }));
        }));
        function messageStatus() {
            if (updateToolbar() === 1) {
                var customerName = document.querySelector('tbody [type="checkbox"]:checked')
                    .closest('tr').querySelector('td:nth-child(2)').innerText;
                console.log(customerName);
                return Swal.fire({
                    text: `Bạn có muốn xóa khách hàng ${customerName} không?`,
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Có, xóa!",
                    cancelButtonText: "Không, hủy bỏ",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                });
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
                    }
                });
            }
        }
        deleteSelectedBtn.addEventListener("click", (function () {
            messageStatus().then((function (result) {
                const headers = {
                    Authorization: `Bearer ${token}`,
                };
                if (result.value) {
                    var list_selected = [];
                    var checkboxed = document.querySelectorAll('tbody [type="checkbox"]');
                    checkboxed.forEach((checkbox => {
                        if (checkbox.checked) {
                            list_selected = [...list_selected, checkbox.closest('tr')
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
                            if (updateToolbar() === 1) {
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
                            }).then((function () {
                                checkboxes.forEach((checkbox => {
                                    if (checkbox.checked) {
                                        datatable.row($(checkbox.closest("tbody tr"))).remove().draw();
                                    }
                                }));
                                document.querySelectorAll('[type="checkbox"]')[0].checked = false;
                                var baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
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
        init: async function () {
            try {
                if (datatable.length)
                    datatable = datatable.DataTable({
                        searchDelay: 500,
                        processing: true,
                        serverSide: true,
                        order: [
                            [1, 'desc']
                        ],
                        stateSave: true,
                        select: {
                            style: 'multi',
                            selector: 'td:first-child input[type="checkbox"]',
                            className: 'row-selected'
                        },
                        ajax: {
                            url: routes.getAllCustomers,
                            type: "POST",
                            beforeSend: function (request) {
                                request.setRequestHeader("Authorization", `Bearer ${token}`);
                            },
                            data: function (data) {
                                data.search = $('input[data-kt-docs-table-filter="search"]').val();
                            }
                        },
                        columns: [
                            {
                                data: 'id'
                            },
                            { data: 'customer.name' },
                            { data: 'customer.phone' },
                            { data: 'card_number' },
                            { data: 'date_due' },
                            { data: 'date_return' },
                            { data: 'null' },
                        ],
                        columnDefs: [{
                            targets: 0,
                            orderable: false,
                            render: function (data) {
                                return `
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="${data}" />
                                </div>`;
                            }
                        },
                        {
                            targets: 1,
                            orderable: false,
                            render: function (data, type, row) {
                                // <a href="{{ route('student-profile.edit', '') }}/${row.id}" class="symbol symbol-50px"></a>
                                return `<span>${data ?? ''}</span>`
                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            render: function (data) {
                                return `<span>${data ?? ''}</span>`;
                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            render: function (data, type, row) {
                                console.log(row);
                                return `<img src="https://api.vietqr.io/img/${row.bank_code}.png" class="h-20px me-3" alt="${row.bank_code}">${row.card_number ?? ''}`;
                            }
                        },
                        {
                            targets: 4,
                            orderable: false,
                            render: function (data) {
                                return `<span>${data ?? ''}</span>`;
                            }
                        },
                        {
                            targets: 5,
                            orderable: false,
                            render: function (data) {
                                return `<span>${data ?? ''}</span>`;
                            }
                        },
                        {
                            targets: 6,
                            orderable: false,
                            render: function (data) {
                                return `<span>${data ?? ''}</span>`;
                            }
                        },
                        {
                            targets: -1,
                            data: null,
                            orderable: false,
                            className: 'text-end',
                            render: function (data, type, row) {
                                return `
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                    Hành động
                                    <span class="svg-icon fs-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="{{ route('student-profile.edit', '') }}/${row.id}" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
                                            Sửa
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-docs-table-filter="delete_row">
                                            Xóa
                                        </a>
                                    </div>
                                </div>
                            `;
                            },
                        },
                        ],
                    });

                //                 const response = await axios.get(routes.getAllCustomers, {
                //                     headers: headers
                //                 });

                //                 if (response.data.success) {
                //                     const customers = response.data.data;

                //                     customers.forEach(customer => {
                //                         const phone = customer.phone;
                //                         var cardNumber = customer.card_number.split(',');
                //                         var dateDue = customer.date_due.split(',');
                //                         var dateReturn = customer.date_return.split(',');
                //                         var bank = customer.bank_code.split(',');
                //                         var satus = customer.status;
                //                         let rowData;

                //                         // if ()

                //                         if (cardNumber.length > 1) {
                //                             let cardNumberHTML = '';
                //                             let dateDueHTML = '';
                //                             let dateReturnHTML = '';
                //                             for (let i = 1; i < cardNumber.length; i++) {
                //                                 if (typeof bank[i] === 'undefined') {

                //                                     cardNumberHTML += `<tr class="hide-num d-none">
                //                                     <td><img src="https://api.vietqr.io/img/${bank[0]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
                //                                     <td>${cardNumber[i]}</td>
                //                                 </tr>`;
                //                                 } else {
                //                                     bank[i] = bank[i].replace(/\s/g, '');
                //                                     cardNumberHTML += `<tr class="hide-num d-none">
                //                                     <td><img src="https://api.vietqr.io/img/${bank[i]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
                //                                     <td>${cardNumber[i]}</td>
                //                                 </tr>`;
                //                                 }
                //                                 dateDueHTML += `<tr class="hide-due d-none"><td>${dateDue[i]}</td></tr>`;
                //                                 dateReturnHTML += `<tr class="hide-return d-none"><td class="align-middle">${dateReturn[i]}</td></tr>`;
                //                             }

                //                             rowData = [
                //                                 `<div class="form-check form-check-sm form-check-custom form-check-solid">
                //                                 <input class="form-check-input" type="checkbox" value="1">
                //                             </div>`,
                //                                 `<table>
                //                                 <tr>
                //                                     <td>${customer.name}</td>
                //                                 </tr>
                //                                 <tr class="hide_name d-none"><td class="invisible">1</td></tr>
                //                             </table>`,
                //                                 `<table>
                //                                 <tr>
                //                                     <td>${phone}</td>
                //                                 </tr>
                //                                 <tr class="hide_phone d-none"><td class="invisible">2</td></tr>
                //                             </table>`,
                //                                 `<table class="see-more-number">
                //                                 <tr>
                //                                     <td><img src="https://api.vietqr.io/img/${bank[0]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
                //                                     <td>${cardNumber[0]}</td>
                //                                 </tr>
                //                                 ${cardNumberHTML}
                //                             </table>`,
                //                                 `<table class="see-more-date-due">
                //                                 <tr><td>${dateDue[0]}</td></tr>
                //                                 ${dateDueHTML}
                //                             </table>`,
                //                                 `<table class="see-more-date-return">
                //                                 <tr><td>${dateReturn[0]}</td></tr>
                //                                 ${dateReturnHTML}
                //                             </table>`,
                //                                 `<table><td><button class="btn dropdown-toggle button-see-more " data-target="${phone}"></button></td>
                //                                 <td><div class="dropdown">
                //                                   <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                //                                     Nhắc nợ
                //                                   </button>
                //                                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                //                                     <a class="dropdown-item" href="#">Action</a>
                //                                     <a class="dropdown-item" href="#">Another action</a>
                //                                     <a class="dropdown-item" href="#">Something else here</a>
                //                                   </div></td>
                //                                 </div>
                //                                 </table>`,
                //                                 `<table class="see-more-button">
                //                                 <tr>
                //                                     <td><button class="btn btn-warning button-edit" data-target="${phone}">Sửa</button></td>

                //                                 </tr>
                //                             </table>`,

                //                             ];
                //                         } else {
                //                             rowData = [
                //                                 `<div class="form-check form-check-sm form-check-custom form-check-solid">
                //                                 <input class="form-check-input" type="checkbox" value="1">
                //                             </div>`,
                //                                 customer.name,
                //                                 customer.phone,
                //                                 ` <td><img src="https://api.vietqr.io/img/${bank}.png"
                //                                    class="rounded-circle h-20px me-2" alt="image"></td>
                //                               <td>${customer.card_number}</td>`,
                //                                 customer.date_due,
                //                                 customer.date_return,
                //                                 `<table><td><button class="btn invisible" data-target="${phone}">1</button></td>
                // <td><div class="dropdown">
                //                                   <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                //                                     Nhắc nợ
                //                                   </button>
                //                                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                //                                     <a class="dropdown-item" href="#">Action</a>
                //                                     <a class="dropdown-item" href="#">Another action</a>
                //                                     <a class="dropdown-item" href="#">Something else here</a>
                //                                   </div>
                //                                 </div></td></table>`,
                //                                 `<table class="see-more-button">
                //                                     <tr>
                //                                 <td><button class="btn btn-warning button-edit" data-target="${phone}">Sửa</button></td>
                //                                 <td><button class="btn invisible" data-target="${phone}">1</button></td>
                //                                 </tr>
                //                         </table>`,

                //                             ];
                //                         }
                //                         datatable.row.add(rowData);

                //                     })
                //                     datatable.draw();
                //                     $('.button-see-more').on('click', function() {
                //                         const $row = $(this).closest('table').parent().parent();
                //                         $row.find('.hide-num').toggleClass('d-none');
                //                         $row.find('.hide-due').toggleClass('d-none');
                //                         $row.find('.hide-return').toggleClass('d-none');
                //                         $row.find('.hide_name').toggleClass('d-none');
                //                         $row.find('.hide_phone').toggleClass('d-none');
                //                         $row.find('.hide-button').toggleClass('d-none');
                //                     });
                //                 } else {
                //                     console.error('Request failed with status:', response.status);
                //                 }
            } catch (error) {
                console.error('Request failed with error:', error);
            }
            initDeleteSelected();
            document.querySelector('[data-kt-customer-table-filter="search"]').addEventListener("keyup", (function (event) {
                datatable.search(event.target.value).draw();
            }));
        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    CustomerList.init();
}));
