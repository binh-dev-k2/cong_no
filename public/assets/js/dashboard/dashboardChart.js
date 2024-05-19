"use strict";

const dashboard = function () {
    const headers = {
        Authorization: 'Bearer ' + token,
    };

    const initDonutChart = function () {
        axios.get(routes.getChartCustomer, { headers })
            .then((response) => {
                console.log(response);
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


    return {
        init: function () {
            initDonutChart();
            initTotalDebit();
            inittotalBusiness();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    dashboard.init();
});




