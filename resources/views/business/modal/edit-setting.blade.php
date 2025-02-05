<div class="modal-content">
    <form action="#" id="form-edit-setting">
        <div class="modal-header">
            <h4 class="modal-title">Sửa tiền chia</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7" style="max-height: calc(100vh - 30rem)">
                <div class="content">
                    <?php
                    $businessSettingMoney = \App\Models\BusinessSetting::where('type', 'MONEY')->orderBy('value')->get()->groupBy('key') ?? [];
                    $businessSettingPercent = \App\Models\BusinessSetting::where('type', 'PERCENT')->orderBy('value')->get()->groupBy('key') ?? [];
                    ?>
                    @isset($businessSettingMoney)
                        @foreach ($businessSettingMoney as $key => $businessSetting)
                            <div class="card mb-3 business-setting-container">
                                <div class="card-header d-flex justify-content-end" style="min-height: 40px!important">
                                    <button type="button" class="btn btn-danger btn-sm delete-business-setting"
                                        style="margin-right: -30px">
                                        <i class="bi bi-x-circle p-0"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column mb-3 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Loại</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio" value="MONEY" name="type" checked="{{ $busi }}"
                                                    required />
                                                <label class="form-check-label">
                                                    Khoảng giá
                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio" value="PERCENT"
                                                    name="type" checked="{{ $busi }}" required />
                                                <label class="form-check-label">
                                                    % phí
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column mb-3 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Mốc (Triệu)</label>
                                        <input type="number" class="form-control form-control-solid"
                                            value="{{ $key }}" name="key" placeholder="Khoảng giá (triệu)"
                                            required>
                                    </div>

                                    <div class="d-flex flex-column mb-3 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Khoảng nhỏ</label>
                                        <input type="text" class="form-control form-control-solid" min="0"
                                            value="{{ $businessSetting[0]['value'] }}" placeholder="Ex: 34000000"
                                            name="min" required data-type="money" />
                                    </div>
                                    <div class="d-flex flex-column mb-3 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Khoảng lớn</label>
                                        <input type="text" class="form-control form-control-solid" min="0"
                                            value="{{ $businessSetting[1]['value'] }}" placeholder="Ex: 35000000"
                                            name="max" required data-type="money" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endisset

                    @isset($businessSettingPercent)
                        @foreach ($businessSettingPercent as $key => $businessSetting)
                            <div class="card mb-3 business-setting-container">
                                <div class="card-header d-flex justify-content-end" style="min-height: 40px!important">
                                    <button type="button" class="btn btn-danger btn-sm delete-business-setting"
                                        style="margin-right: -30px">
                                        <i class="bi bi-x-circle p-0"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column mb-3 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Mốc (Triệu)</label>
                                        <input type="number" class="form-control form-control-solid"
                                            value="{{ $key }}" name="key" placeholder="Khoảng giá (triệu)"
                                            required>
                                    </div>

                                    @foreach ($businessSetting as $bs)
                                        <div class="d-flex flex-column mb-3 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">% phí</label>
                                            <input type="text" class="form-control form-control-solid" min="0"
                                                value="{{ $bs['value'] }}" placeholder="Ex: 10%" name="fee_percent"
                                                required data-type="money" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endisset
                    <div class="w-100">
                        <button type="button"
                            class="btn btn-primary btn-sm my-3 add-business-setting w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-plus-circle p-0 me-2"></i>
                            Thêm
                        </button>
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

<template id="business-setting-template">
    <div class="card mb-3 business-setting-container">
        <div class="card-header d-flex justify-content-end" style="min-height: 40px!important">
            <button type="button" class="btn btn-danger btn-sm delete-business-setting" style="margin-right: -30px">
                <i class="bi bi-x-circle p-0"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column mb-3 fv-row">
                <label class="required fs-6 fw-semibold mb-2">Loại</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="type" required>
                    <label class="form-check-label">
                        1
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="5" name="type" required>
                    <label class="form-check-label">
                        5
                    </label>
                </div>
            </div>

            <div class="d-flex flex-column mb-3 fv-row">
                <label class="required fs-6 fw-semibold mb-2">Mốc (Triệu)</label>
                <input type="number" class="form-control form-control-solid" value="" name="key[]"
                    placeholder="Khoảng giá (triệu)" required>
            </div>

            <div class="d-flex flex-column mb-3 fv-row">
                <label class="required fs-6 fw-semibold mb-2">Khoảng nhỏ</label>
                <input type="text" class="form-control form-control-solid" min="0" value=""
                    placeholder="Ex: 34000000" name="min" required data-type="money" />
            </div>

            <div class="d-flex flex-column mb-3 fv-row">
                <label class="required fs-6 fw-semibold mb-2">Khoảng lớn</label>
                <input type="text" class="form-control form-control-solid" min="0" value=""
                    placeholder="Ex: 35000000" name="max" required data-type="money" />
            </div>
        </div>
    </div>
</template>

<script>
    $(document).ready(function() {
        const headers = {
            Authorization: `Bearer ${token}`,
        };

        const $modalEditSetting = $('#modal-edit-setting');

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

        $(document).off('submit', '#form-edit-setting');

        $(document).on('submit', '#form-edit-setting', function(e) {
            e.preventDefault();

            const businessSettings = [];
            $modalEditSetting.find('input[name="key[]"]').each(function() {
                const key = parseInt($(this).val());
                const min = parseInt($(this).closest('.card').find('input[name="min[]"]')
                    .val());
                const max = parseInt($(this).closest('.card').find('input[name="max[]"]')
                    .val());

                businessSettings.push({
                    key,
                    value: min
                })
                businessSettings.push({
                    key,
                    value: max
                });
            });

            if (businessSettings.length == 0) {
                notify("Vui lòng nhập thống tin", 'error');
                return;
            }

            const $submitButton = $(this).find('button[type="submit"]');
            $submitButton.attr('data-kt-indicator', "on").prop('disabled', true);
            const body = businessSettings

            axios.post("{{ route('api.business.updateSetting') }}", body, {
                    headers: headers
                })
                .then((res) => {
                    if (res.data.code == 0) {
                        $modalEditSetting.modal('hide');
                        $modalEditSetting.find('form')[0].reset();
                    } else {
                        notify(res.data.data.join(', '), 'error');
                    }
                })
                .catch((err) => {
                    console.log(err);
                    notify(err.message, 'error');
                })
                .finally(() => {
                    $submitButton.attr('data-kt-indicator', "off").prop('disabled', false);
                })
        })

        $(document).on('click', '.delete-business-setting', function(e) {
            e.preventDefault();
            $(this).closest('.card').remove();
        })

        $('.add-business-setting').on('click', function(e) {
            e.preventDefault();

            const businessSettingElement = $('#business-setting-template').clone().html();
            $('#form-edit-setting').find('.add-business-setting').before(businessSettingElement);
        })
    })
</script>
