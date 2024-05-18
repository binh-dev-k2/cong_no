"use strict";

// Class definition
const KTCardsWidget4 = function () {
    const headers = {
        Authorization: 'Bearer ' + token,
    };
    const initDonutChart = function () {
        axios.get(routes.getDonutChartData, {headers})
            .then((response) => {
                let ctx = document.getElementById('kt_card_widget_4_chart');
                document.getElementById('totalCustomers').innerText = response.data.totalData;
                const labels = ['Sắp đến hạn', 'Đã nhắc', 'Chưa nhắc'];
                const data = {
                    labels: labels,
                    datasets: [
                        {
                            backgroundColor: ['#CB4335', '#1F618D', '#F1C40F'],
                            data: [response.data.totalDataDue, response.data.totalRemind, response.data.totalNotRemind]
                        }
                    ]
                };
                console.log(data)
                const totalData = 1024
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
                const myChart = new Chart(ctx, config);
            })

    };
    const initProcessChart = function () {
        axios.get(routes.getProcessData,{headers})
            .then((response) => {
                let percentComplete = response.data.percent;
                let isDone = response.data.isDone;
                let totalMoney = response.data.totalMoney;
                const formattedVND = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalMoney);

                document.getElementById('totalMoney').innerText = formattedVND;
                document.getElementById('process-data-complete1').innerText = percentComplete + '%';
                document.getElementById('process-data-complete2').style.width = percentComplete + '%';
                document.getElementById('isDoneDebit').innerText = isDone+' đã thu';
            })
    };

    const inittotalBusiness = function () {
        axios.get(routes.getTotalBusiness,{headers})
            .then((response) => {
                let totalBusiness = response.data.totalBusiness;
                document.getElementById('totalBusiness').innerText = totalBusiness;
            })
    };


    // Public methods
    return {
        init: function () {
            initDonutChart();
            initProcessChart();
            inittotalBusiness();
        }
    }
}();


// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTCardsWidget4.init();
});




