"use strict";

var CustomerList = function () {
    let timeoutSearch;
    const drawer_note = document.querySelector("#drawer_note");
    let dt_name = '', dt_phone = ''

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

        // deleteSelectedBtn.addEventListener("click", (function () {
        //     messageStatus().then((function (result) {
        //         const headers = {
        //             Authorization: `Bearer ${token}`,
        //         };
        //         if (result.value) {
        //             var list_selected = [];
        //             var checkboxed = document.querySelectorAll('tbody [type="checkbox"]');
        //             checkboxed.forEach((checkbox => {
        //                 if (checkbox.checked) {
        //                     list_selected = [...list_selected, checkbox.closest('tr')
        //                         .querySelector('.button-edit').getAttribute('data-target')];
        //                 }
        //             }));
        //             const deletePromises = list_selected.map((customerPhone) => {
        //                 const url = delete_customer_route.replace(':phone', customerPhone);
        //                 return axios.delete(url, { headers: headers });
        //             });
        //             Promise.all(deletePromises)
        //                 .then((response) => {
        //                     var notiMessage = '';
        //                     if (updateToolbar() === 1) {
        //                         var customerName = document.querySelector('tbody [type="checkbox"]:checked')
        //                             .closest('tr').querySelector('td:nth-child(2)').innerText;
        //                         notiMessage = `Khách hàng ${customerName} đã bị xóa!`;
        //                     } else {
        //                         notiMessage = 'Tất cả khách hàng đã chọn đã bị xóa!';
        //
        //                     }
        //                     Swal.fire({
        //                         text: notiMessage,
        //                         icon: "success",
        //                         buttonsStyling: false,
        //                         confirmButtonText: "Ok!",
        //                         customClass: {
        //                             confirmButton: "btn fw-bold btn-primary"
        //                         }
        //                     }).then((function () {
        //                         checkboxes.forEach((checkbox => {
        //                             if (checkbox.checked) {
        //                                 datatable.row($(checkbox.closest("tbody tr"))).remove().draw();
        //                             }
        //                         }));
        //                         document.querySelectorAll('[type="checkbox"]')[0].checked = false;
        //                         var baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
        //                         var selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
        //                         var selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
        //                         selectedCount.innerHTML = '';
        //                         baseToolbar.classList.remove("d-none");
        //                         selectedToolbar.classList.add('d-none');
        //                     }));
        //                 })
        //         } else if (result.dismiss === "cancel") {
        //             Swal.fire({
        //                 text: "Không có khách hàng nào bị xóa.",
        //                 icon: "error",
        //                 buttonsStyling: false,
        //                 confirmButtonText: "Ok!",
        //                 customClass: {
        //                     confirmButton: "btn fw-bold btn-primary"
        //                 }
        //             });
        //         }
        //     }));
        // }));
    };

    const handleSearchDatatable = () => {
        document.querySelector('[data-kt-customer-table-filter="search"]')
            .addEventListener("keyup", (function (e) {
                clearTimeout(timeoutSearch)
                timeoutSearch = setTimeout(function () {
                    datatable.draw();
                }, 500)
            }));
    }

    const initRemindDrawer = () => {
        let drawer_btns = document.querySelectorAll('.drawer-remind-btn');
        const drawer_element = document.querySelector("#drawer_remind");
        const drawer = KTDrawer.getInstance(drawer_element);

        drawer_btns.forEach((btn) => {
            btn.addEventListener('click', function () {
                drawer_element.querySelector('input[name="drawer-id"]').value = this.getAttribute('data-id')
                drawer.toggle();
            })
        })
    }

    return {
        initDatatable: async function () {
            datatable = $("#kt_debit_table").DataTable({
                fixedColumns: {
                    leftColumns: 1,
                    rightColumns: 1
                },
                // scrollCollapse: true,
                // scrollX: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                order: [
                    // [2, 'desc']
                ],
                stateSave: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    className: 'row-selected'
                },
                ajax: {
                    url: routes.getAllDebitCards,
                    type: "POST",
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", `Bearer ${token}`);
                    },
                    data: function (d) {
                        d.search = $('input[data-kt-customer-table-filter]').val();
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        data: 'formality',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 1,
                        data: 'customer.name',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 2,
                        data: 'card_number',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 3,
                        data: 'fee_percent',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 4,
                        data: 'total_money',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        data: 'pay_extra',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return ` <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" />
                            </div>`;
                        }
                    },
                ]
            });

            // Re-init functions
            datatable.on('draw', function () {
                initDeleteSelected();
                handleSearchDatatable()
            })
        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    CustomerList.initDatatable();
}));
