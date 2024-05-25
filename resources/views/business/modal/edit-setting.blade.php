<div class="modal-content">
    <form action="#" method="post">
        <div class="modal-header">
            <h4 class="modal-title">Sửa tiền chia</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-10 px-lg-17">
            <div class="scroll-y me-n7 pe-7" style="max-height: calc(100vh - 30rem)">
                <div class="content">
                    <div class="d-flex flex-column mb-3 fv-row">
                        <label class="required fs-6 fw-semibold mb-2" for="business_min">Min</label>
                        <input type="number" class="form-control form-control-solid" min="0"
                            value="{{ $min }}" placeholder="Ex: 34000000" name="business_min" id="business_min"
                            required />
                    </div>

                    <div class="d-flex flex-column mb-3 fv-row">
                        <label class="required fs-6 fw-semibold mb-2" for="business_max">Max</label>
                        <input type="number" class="form-control form-control-solid" min="0"
                            value="{{ $max }}" placeholder="Ex: 35000000" name="business_max" id="business_max"
                            required />
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

        $modalEditSetting.on('submit', 'form', function(e) {
            e.preventDefault();
            $(this).find('button[type="submit"]').attr('data-kt-indicator', "on");

            const body = {
                business_min: parseInt($modalEditSetting.find('input[name="business_min"]').val()),
                business_max: parseInt($modalEditSetting.find('input[name="business_max"]').val()),
            }

            axios.post("{{ route('api.business.updateSetting') }}", body, {
                    headers: headers
                })
                .then((res) => {
                    if (res.data.code == 0) {
                        // notify("Sửa nghiệp vụ thành công!", 'success');
                        $modalEditSetting.modal('hide');
                        datatable.draw();
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
                    $(this).find('button[type="submit"]').attr('data-kt-indicator', "off");
                })
        })
    })
</script>
