<div class="modal-content">
    <form action="#" id="form-edit-setting">
        <div class="modal-header">
            <h4 class="modal-title">Sửa tiền chia</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7" style="max-height: calc(100vh - 30rem)">
                <div class="content">
                    @foreach ($businessMoneys as $key => $businessMoney)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex flex-column mb-3 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Mốc (Triệu)</label>
                                    <input type="number" class="form-control form-control-solid"
                                        value="{{ $key }}" name="key[]" placeholder="Khoảng giá (triệu)">
                                </div>

                                <div class="d-flex flex-column mb-3 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Khoảng nhỏ</label>
                                    <input type="number" class="form-control form-control-solid" min="0"
                                        value="{{ $businessMoney[0]['value'] }}" placeholder="Ex: 34000000"
                                        name="min[]" required />
                                </div>

                                <div class="d-flex flex-column mb-3 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Khoảng lớn</label>
                                    <input type="number" class="form-control form-control-solid" min="0"
                                        value="{{ $businessMoney[1]['value'] }}" placeholder="Ex: 35000000"
                                        name="max[]" required />
                                </div>
                            </div>
                        </div>
                    @endforeach
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

            const $submitButton = $(this).find('button[type="submit"]');
            $submitButton.attr('data-kt-indicator', "on").prop('disabled', true);

            const businessMoneys = [];
            $modalEditSetting.find('input[name="key[]"]').each(function() {
                const key = parseInt($(this).val());
                const min = parseInt($(this).closest('.card').find('input[name="min[]"]')
                    .val());
                const max = parseInt($(this).closest('.card').find('input[name="max[]"]')
                    .val());

                businessMoneys.push({
                    key,
                    value: min
                })
                businessMoneys.push({
                    key,
                    value: max
                });
            });

            const body = businessMoneys

            axios.post("{{ route('api.business.updateSetting') }}", body, {
                    headers: headers
                })
                .then((res) => {
                    if (res.data.code == 0) {
                        // notify("Sửa nghiệp vụ thành công!", 'success');
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
    })
</script>
