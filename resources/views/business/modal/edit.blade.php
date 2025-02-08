<style>
    /* Chrome, Safari, Edge, Opera */
    #modal_edit input::-webkit-outer-spin-button,
    #modal_edit input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    #modal_edit input[type=number] {
        -moz-appearance: textfield;
    }

    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .search-results li:hover {
        background-color: #f0f0f0;
        cursor: pointer;
    }
</style>


<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="#" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Sửa nghiệp vụ</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" style="max-height: calc(100vh - 30rem)">
                        <div class="content">
                            <input type="hidden" name="id" value="">
                            <div class="mb-3 fv-row position-relative search-container">
                                <div class="d-flex flex-column position-relative">
                                    <label class="required fs-6 fw-semibold mb-2" for="card_number">Số thẻ</label>
                                    <input type="number" class="form-control form-control-solid" minlength="16"
                                        maxlength="16" min="0" placeholder="Ex: ************1234"
                                        name="card_number" id="card_number" required readonly />
                                    <div class="position-absolute top-50 end-0 me-3">
                                        <i class="ki-duotone ki-credit-cart fs-2hx">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>

                                <ul class="search-results list-unstyled shadow-sm rounded mw-650px"></ul>
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2" for="account_name">Chủ tài khoản</label>
                                <input type="text" class="form-control form-control-solid"
                                    placeholder="Ex: Tran Van A" name="account_name" id="account_name" />
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="required fs-6 fw-semibold mb-2" for="name">Tên khách hàng</label>
                                <input type="text" class="form-control form-control-solid"
                                    placeholder="Ex: Tran Van A" name="name" id="name" required />
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="required fs-6 fw-semibold mb-2" for="phone">SĐT</label>
                                <input type="number" class="form-control form-control-solid" min="0"
                                    placeholder="Ex: 0123456789" name="phone" id="phone" required />
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="required fs-6 fw-semibold mb-2" for="fee_percent">% phí</label>
                                <input type="number" class="form-control form-control-solid" placeholder="Ex: 21"
                                    step="any" name="fee_percent" id="fee_percent" required />
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Hình thức</label>
                                <div class="d-flex">
                                    <div class="form-check me-5">
                                        <input class="form-check-input" type="radio" value="Đ" name="formality">
                                        <label class="form-check-label" for="type_old">
                                            Đáo
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="R" name="formality">
                                        <label class="form-check-label" for="type_new">
                                            Rút
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="required fs-6 fw-semibold mb-2" for="machine_id">Mã máy</label>
                                <select class="form-select form-select-solid" name="machine_id" id="machine_id">
                                    @foreach ($machines as $key => $machine)
                                        <option value="{{ $machine['id'] }}">{{ $machine['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="required fs-6 fw-semibold mb-2" for="total_money">Số tiền</label>
                                <input type="number" class="form-control form-control-solid"
                                    placeholder="Ex: 1000000" name="total_money" id="total_money" required
                                    readonly />
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Khoảng chia</label>
                                @isset($businessMoneys['MONEY'])
                                    <span class="text-muted fw-bold mb-2">Theo khoảng</span>
                                    @foreach ($businessMoneys['MONEY'] as $key => $businessMoney)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" value="{{ $key }}"
                                                data-type="MONEY" name="business_setting_key">
                                            <label class="form-check-label">
                                                {{ $key }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endisset

                                @isset($businessMoneys['PERCENT'])
                                    <span class="text-muted fw-bold mb-2">Theo %</span>
                                    @foreach ($businessMoneys['PERCENT'] as $key => $businessMoney)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" value="{{ $key }}"
                                                data-type="PERCENT" name="business_setting_key">
                                            <label class="form-check-label">
                                                {{ $key }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">
                            Xác nhận
                        </span>
                        <span class="indicator-progress">
                            Loading... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const headers = {
            Authorization: token,
        };

        let timeOutSearchCard = null;
        const $modalEditBusiness = $('#modal-edit');
        const $results = $modalEditBusiness.find('.search-results');

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

        $modalEditBusiness.find('input[name="card_number"]').on('keyup', function() {
            clearTimeout(timeOutSearchCard);
            timeOutSearchCard = setTimeout(() => {
                axios.post("{{ route('api.card.find') }}", {
                        search: $(this).val()
                    }, {
                        headers: headers
                    })
                    .then((res) => {
                        $results.empty();

                        res.data?.data.forEach(card => {
                            const image =
                                `<img src="${card.bank.logo}" class="h-20px mb-1" style="min-width: 52px" alt="image"/>`;
                            const text =
                                `${card.card_number} ${card.customer ? `- ${card.customer.name} - ${card.customer.phone}` : ''}`;

                            const $li = $('<li>').html(image + text).addClass('p-3')
                                .data(card);
                            $results.append($li);
                        });

                        if ($results.children().length > 0) {
                            $results.show();
                        } else {
                            $results.hide();
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                        $results.hide();
                    })
            }, 500);
        })

        $modalEditBusiness.on('click', '.search-results li', function() {
            bodyBusinessData = {};
            const data = $(this).data();

            $modalEditBusiness.find('input[name="card_number"]').val(data.card_number);
            $modalEditBusiness.find('input[name="account_name"]').val(data.account_name ?? '');
            $modalEditBusiness.find('input[name="name"]').val(data.customer?.name ?? '');
            $modalEditBusiness.find('input[name="phone"]').val(data.customer?.phone ?? '');
            $modalEditBusiness.find('input[name="fee_percent"]').val(data.customer?.fee_percent ?? '');
            $modalEditBusiness.find('.search-results').hide();
        });

        $modalEditBusiness.on('submit', 'form', function(e) {
            e.preventDefault();
            $(this).find('button[type="submit"]').attr('data-kt-indicator', "on");

            const body = {
                id: $modalEditBusiness.find('input[name="id"]').val(),
                card_number: $modalEditBusiness.find('input[name="card_number"]').val(),
                account_name: $modalEditBusiness.find('input[name="account_name"]').val(),
                name: $modalEditBusiness.find('input[name="name"]').val(),
                phone: $modalEditBusiness.find('input[name="phone"]').val(),
                fee_percent: parseFloat($modalEditBusiness.find('input[name="fee_percent"]').val()),
                formality: $modalEditBusiness.find('input[name="formality"]:checked').val(),
                machine_id: $modalEditBusiness.find('select[name="machine_id"]').val(),
                total_money: parseInt($modalEditBusiness.find('input[name="total_money"]').val()
                    .replace(/[.,]/g, ''), 10),
                business_setting_key: $modalEditBusiness.find(
                    'input[name="business_setting_key"]:checked').val(),
                business_setting_type: $modalEditBusiness.find(
                    'input[name="business_setting_key"]:checked').data('type')
            }

            // console.log(body);

            axios.post("{{ route('api.business.update') }}", body, {
                    headers: headers
                })
                .then((res) => {
                    if (res.data.code == 0) {
                        // notify("Sửa nghiệp vụ thành công!", 'success');
                        prevPhone = null
                        $modalEditBusiness.modal('hide');
                        datatable.draw();
                        $modalEditBusiness.find('form')[0].reset();
                    } else {
                        notify(res.data.data.join(', '), 'error');
                    }
                })
                .catch((err) => {
                    console.log(err);
                    notify(err.message, 'error');
                })
                .finally(() => {
                    $(this).find('button[type="submit"]').attr('data-kt-indicator', "off");
                })
        })
    })
</script>
