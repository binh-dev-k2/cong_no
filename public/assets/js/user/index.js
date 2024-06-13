"use strict";

var UserList = function () {
    var timeoutSearch;

    const headers = {
        Authorization: `Bearer ${token}`,
    };

    const handleSearchDatatable = () => {
        $('#user_search').on("keyup", (function (e) {
            clearTimeout(timeoutSearch)
            timeoutSearch = setTimeout(function () {
                datatable.draw();
            }, 500)
        }));
    }


    const formatTime = (time) => {
        const dateTime = new Date(time);
        const year = dateTime.getFullYear();
        const month = String(dateTime.getMonth() + 1).padStart(2, "0");
        const day = String(dateTime.getDate()).padStart(2, "0");
        const hour = String(dateTime.getHours()).padStart(2, "0");
        const minute = String(dateTime.getMinutes()).padStart(2, "0");
        return `${day}-${month}-${year} ${hour}:${minute}`;
    }

    const initDelete = () => {
        const deleteBtns = document.querySelectorAll('.btn-delete');
        deleteBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                const row = btn.closest('tr');
                const data = datatable.row(row).data();
                const id = data.id

                notify('Bạn có chắc muốn xóa người dùng này?', 'warning', true).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(routes.userDelete, { id: id }, { headers: headers })
                            .then((res) => {
                                if (res.data.code == 0) {
                                    datatable.draw();
                                } else {
                                    notify(res.data.data.join(", ") ?? 'Có lỗi xảy ra, vui lòng thử lại sau!', 'error')
                                }
                            })
                            .catch((err) => {
                                console.log(err);
                                notify(err.message, 'error')
                            })
                    }
                })
            })
        })
    }

    // const initEdit = () => {
    //     const editBtns = document.querySelectorAll('.btn-edit');
    //     editBtns.forEach((btn) => {
    //         btn.addEventListener('click', (e) => {
    //             const row = btn.closest('tr');
    //             const data = datatable.row(row).data();

    //             const modalEdit = document.querySelector('#modal-edit');
    //             modalEdit.querySelector('input[name="id"]').value = data.id;
    //             modalEdit.querySelector('input[name="card_number"]').value = data.card_number;
    //             modalEdit.querySelector('input[name="account_name"]').value = data.account_name;
    //             modalEdit.querySelector('input[name="name"]').value = data.name;
    //             modalEdit.querySelector('input[name="phone"]').value = data.phone;
    //             modalEdit.querySelector('input[name="fee_percent"]').value = data.fee_percent;
    //             data.formality ? modalEdit.querySelector('input[name="formality"][value="' + data.formality + '"]').checked = true : '';
    //             modalEdit.querySelector('input[name="total_money"]').value = data.total_money;
    //         })
    //     })
    // }

    const notify = (text, type = 'success', showCancelButton = false) => {
        return Swal.fire({
            text: text,
            icon: type,
            buttonsStyling: !1,
            showCancelButton: showCancelButton,
            confirmButtonText: "Xác nhận",
            cancelButtonText: "Đóng",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-light"
            }
        })
    }

    return {
        initDatatable: async function () {
            datatable = $("#user_table").DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: routes.datatable,
                    type: "POST",
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", `Bearer ${token}`);
                    },
                    data: function (d) {
                        d.search = $('#user_search').val();
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row, meta) {
                            return `<span>${meta.row + meta.settings._iDisplayStart + 1}</span>`
                        }
                    },
                    {
                        targets: 1,
                        data: 'name',
                        orderable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<span>${data ?? ''}</span>`
                        }
                    },
                    {
                        targets: 2,
                        data: 'email',
                        orderable: false,
                        className: 'min-w-150px',
                        render: function (data, type, row) {
                            return `<span>${data}</span>`
                        }
                    },
                    {
                        targets: 3,
                        data: 'created_at',
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function (data, type, row) {
                            return `<span>${formatTime(data)}</span>`;
                        }
                    },
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        className: 'text-center min-w-150px',
                        render: function (data, type, row) {
                            return `
                                    <button class="btn btn-danger btn-active-light-danger btn-sm btn-delete">Xóa</button>
                                `;
                        },
                    },
                ],
            });

            // Re-init functions
            datatable.on('draw', function () {
                initDelete()
                // initEdit()
            })
            handleSearchDatatable()
        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    UserList.initDatatable();
}));
