"use strict";

const dashboard = function () {
    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    };

    function formatNumber(number) {
        // Chuyển số sang chuỗi nếu nó chưa phải là chuỗi
        const str = number.toString();

        // Sử dụng biểu thức chính quy để chia thành từng nhóm 4 chữ số
        const formattedStr = str.replace(/(.{4})/g, '$1 ');

        // Cắt bỏ khoảng trắng cuối cùng nếu có
        return formattedStr.trim();
    }

    const formatter = new Intl.NumberFormat('en-US', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

    $(document).on('input', 'input[data-type="money"]', function () {
        const value = $(this).val().replace(/[^0-9]/g, '');
        if (value === '') {
            $(this).val('');
        } else {
            $(this).val(formatter.format(parseInt(value)));
        }
    });

    const initTotalInvestment = function () {
        const getTotalInvestment = () => {
            axios.get(routes.getTotalInvestment, { headers })
                .then((response) => {
                    const data = response.data;
                    const totalInvestment = document.getElementById('total-investment');
                    totalInvestment.innerText = formatter.format(parseFloat(data.totalInvestment)) + ' VNĐ';
                })
                .catch((error) => {
                    console.log(error);
                })
        }

        getTotalInvestment();

        $('#btn-update-total-investment').on('click', function () {
            $('#modal-update-total-investment').modal('show');
        })

        $('#form-update-total-investment').on('submit', function (e) {
            e.preventDefault();
            const money = $('#investment-value').val().replace(/,/g, '');
            axios.post(routes.updateTotalInvestment, { value: money }, { headers })
                .then((response) => {
                    if (response.data.code == 0) {
                        toastr.success('Thêm quỹ thành công');
                        getTotalInvestment();
                        $('#modal-update-total-investment').modal('hide');
                    } else {
                        toastr.error('Thêm quỹ thất bại', response.data.message);
                    }
                })
                .catch((error) => {
                    toastr.error('Thêm quỹ thất bại', error.response.data.message);
                    console.log(error);
                })
        })
    };

    const initDonutChart = function () {
        axios.get(routes.getChartCustomer, { headers })
            .then((response) => {
                let ctx = document.getElementById('canvas-chart-customer');
                document.getElementById('chart-customer').innerText = response.data.totalCanBeRemind;
                const labels = ['Đã nhắc', 'Chưa nhắc'];
                const data = {
                    labels: labels,
                    datasets: [
                        {
                            backgroundColor: ['#1F618D', '#CB4335'],
                            data: [response.data.totalHasBeenReminded, response.data.totalNotReminding]
                        }
                    ]
                };

                const config = {
                    type: 'doughnut',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                        }
                    },
                }
                new Chart(ctx, config);
            })

    };

    const initTotalDebit = function () {
        axios.get(routes.getTotalDebit, { headers })
            .then((response) => {
                const percentCompleted = response.data.percentCompleted;
                const countPaidedDebit = response.data.countPaidedDebit;
                const totalAmount = response.data.totalAmount;
                const formattedVND = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalAmount);

                document.getElementById('total-debit').innerText = formattedVND;
                document.getElementById('process-data-complete1').innerText = percentCompleted + '%';
                document.getElementById('process-data-complete2').style.width = percentCompleted + '%';
                document.getElementById('count-paid-debit').innerText = countPaidedDebit + ' đã thu';
            })
    };

    const initTotalBusiness = function () {
        axios.get(routes.getTotalBusiness, { headers })
            .then((response) => {
                let totalBusiness = response.data.totalBusiness;
                document.getElementById('total-business').innerText = totalBusiness;
            })
    };

    const initTotalMachineFee = function (month = null, year = null) {
        axios.post(routes.getMachineFee, { month, year }, { headers })
            .then((response) => {
                let total = response.data.total;
                const formattedVND = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total ?? 0)
                document.getElementById('total-machine-fee').innerText = formattedVND;
            })
    };

    const initTableCardExpired = function () {
        const datatable = $("#table-card-expired").DataTable({
            // fixedColumns: {
            //     leftColumns: 0,
            //     rightColumns: 1
            // },
            searching: false,
            lengthMenu: [10, 20, 50, 100],
            pageLength: 50,
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: routes.getCardExpired,
                type: "POST",
                beforeSend: function (request) {
                    request.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                },
                data: function (d) {

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
                    data: 'customer',
                    orderable: false,
                    className: 'text-center min-w-150px',
                    render: function (data, type, row, meta) {
                        const phone = data.phone.startsWith('@') ? data.phone.substring(1) : data.phone;
                        const url = data.phone.startsWith('@') ? `https://t.me/${phone}` : `https://zalo.me/${phone}`;
                        return `<div>${data.name}</div>
                                    <a href="${url}" target="_blank">${phone}</a>`;
                    }
                },
                {
                    targets: 2,
                    data: null,
                    orderable: false,
                    className: 'text-center min-w-175px',
                    render: function (data, type, row, meta) {
                        return `<div class="d-flex flex-column align-items-center">
                                    <img src="${row.bank.logo}" loading="lazy" class="h-30px" alt="${row.bank.code}">
                                    <span>${formatNumber(row.card_number)}</span>
                                    ${formatNumber(row.account_number ? 'STK: ' + row.account_number : '')}
                                </div>
                                `;
                    }
                },
                {
                    targets: 3,
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function (data, type, row, meta) {
                        return `<span>${data.month_expired} - ${data.year_expired}</span>`;
                    }
                },
            ],
        });
    }

    return {
        init: function () {
            initDonutChart();
            initTotalDebit();
            initTotalBusiness();
            initTotalMachineFee();
            initTableCardExpired();
            initTotalInvestment();

            $('#machine-month-select, #machine-year-select').on('change', function () {
                const month = $('#machine-month-select').val();
                const year = $('#machine-year-select').val();
                initTotalMachineFee(month, year);
            })
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    dashboard.init();
});




