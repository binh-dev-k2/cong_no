document.addEventListener('DOMContentLoaded', async function() {
    try {
        const headers = {
            Authorization: `Bearer ${token}`,
        };

        const response = await axios.get(showAllCustomers, {headers});

        if (response.data.success) {
            const customers = response.data.data;
            displayCustomers(customers);
        } else {
            console.error('Request failed with status:', response.status);
        }
    } catch (error) {
        console.error('Request failed with error:', error);
    }
});

function displayCustomers(customers) {
    const table = $('#kt_customers_table').DataTable();

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
                    cardNumberHTML += `<tr class="d-none">
                                    <td><img src="https://api.vietqr.io/img/${bank[i]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
                                    <td>${cardNumber[i]}</td>
                                </tr>`;
                }
                dateDueHTML += `<tr class="hide-due d-none"><td>${dateDue[i]}</td></tr>`;
                dateReturnHTML += `<tr class="hide-return d-none"><td>${dateReturn[i]}</td></tr>`;
            }

            rowData = [
                `<div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1">
                </div>`,
                customer.name,
                customer.phone,
                `<table class="see-more-number">
                    <tr>
                        <td><img src="https://api.vietqr.io/img/${bank[0]}.png" class="rounded-circle h-20px me-2" alt="image"></td>
                        <td>${cardNumber[0]}</td>
                        <td><div class="btn dropdown-toggle button-see-more" data-toggle="see-more" data-phone="${phone}"></div></td>
                    </tr>
                    ${cardNumberHTML}
                </table>`,
                `<table class="see-more-date-due">
                    <tr>${dateDue[0]}</tr>
                    ${dateDueHTML}
                </table>`,
                `<table class="see-more-date-return">
                    <tr>${dateReturn[0]}</tr>
                    ${dateReturnHTML}
                </table>`,
                `<button class="btn btn-secondary collapse-btn" data-target="${phone}">Collapse</button>`,
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
                `<button class="btn btn-secondary collapse-btn" data-target="${phone}">Collapse</button>`,
            ];
        }

        table.row.add(rowData).draw();
    });


    $('.button-see-more').on('click', function() {
        const $row = $(this).closest('table');
        $row.find('.hide-num').toggleClass('d-none');
        $row.find('.hide-due').toggleClass('d-none');
        $row.find('.hide-return').toggleClass('d-none');
    });




}

