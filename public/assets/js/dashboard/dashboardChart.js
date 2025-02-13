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

    const inittotalBusiness = function () {
        axios.get(routes.getTotalBusiness, { headers })
            .then((response) => {
                let totalBusiness = response.data.totalBusiness;
                document.getElementById('total-business').innerText = totalBusiness;
            })
    };

    const inittotalMachineFee = function () {
        axios.get(routes.getMachineFee, { headers })
            .then((response) => {
                let total = response.data.total;
                const formattedVND = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total)
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
            inittotalBusiness();
            inittotalMachineFee();
            initTableCardExpired();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    dashboard.init();
});




