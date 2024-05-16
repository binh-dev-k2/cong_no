"use strict";

var DebitsList = function () {
    let timeoutSearch;
    // var updateToolbar = () => {
    //     const baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
    //     const selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
    //     const selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
    //     const checkboxes = document.querySelectorAll(' tbody [type="checkbox"]');
    //     let anyChecked = false;
    //     let checkedCount = 0;
    //
    //     checkboxes.forEach((checkbox => {
    //         if (checkbox.checked) {
    //             anyChecked = true;
    //             checkedCount++;
    //         }
    //     }));
    //
    //     if (anyChecked) {
    //         selectedCount.innerHTML = checkedCount;
    //         baseToolbar.classList.add("d-none");
    //         selectedToolbar.classList.remove("d-none");
    //     } else {
    //         baseToolbar.classList.remove("d-none");
    //         selectedToolbar.classList.add("d-none");
    //     }
    //
    //     return checkedCount;
    // };
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
    };

    const handleSearchDatatable = () => {
        document.querySelector('[data-kt-customer-table-filter="search"]')
            .addEventListener("keyup", (function (e) {
                clearTimeout(timeoutSearch)
                timeoutSearch = setTimeout(function () {
                    datatable.draw();
                }, 300)
            }));
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
                        d.search = $('input[data-kt-debit-table-filter]').val();
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        data: 'id',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="id" value="${data}"/>
                                    </div>`;
                        }
                    },
                    {
                        targets: 1,
                        data: 'name',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 2,
                        data: 'phone',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 3,
                        data: 'card_number',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 4,
                        data: 'formality',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 5,
                        data: 'fee',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 6,
                        data: 'total_amount',
                        orderable: false,
                        render: function (data, type, row) {
                           return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 7,
                        data: 'pay_extra',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`;
                        }
                    },
                    {
                        targets: 8,
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            const totalAmount = row.total_amount !== undefined ? row.total_amount : '';
                            const payExtra = row.pay_extra !== undefined ? row.pay_extra : '';
                            return `<span>${(totalAmount + payExtra) ?? ''}</span>`;
                        }
                    },

                    {
                        targets: 9,
                        data: 'status',
                        orderable: false,
                        render: function (data, type, row) {
                            if (data===0){
                                return `<span>Chưa thu</span>`;
                            }
                            return `<span>Đã thu</span>`;
                        }
                    },
                    {
                        targets: 10,
                        data: 'status',
                        orderable: false,
                        render: function (data, type, row) {
                            return `<a href="#" class="btn btn-sm btn-light btn-active-light-primary">Sửa</a>`;
                        }
                    }

                ]
            });
            // console.log('datatable', datatable);
            //
            // // Re-init functions
            // datatable.on('draw', function () {
            //     initDeleteSelected();
            //     handleSearchDatatable();
            // })
        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    DebitsList.initDatatable();
}));
