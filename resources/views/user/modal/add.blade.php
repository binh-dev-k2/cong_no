<style>
    /* Chrome, Safari, Edge, Opera */
    #modal_add input::-webkit-outer-spin-button,
    #modal_add input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    #modal_add input[type=number] {
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


<div class="modal fade" id="modal-add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="#" method="post">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm mới người dùng</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" style="max-height: calc(100vh - 30rem)">
                        <div class="content">
                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2" for="name">Tên</label>
                                <input id="name" type="text"
                                    class="form-control form-control-solid @error('name') is-invalid @enderror"
                                    name="name" value="" required autocomplete="name" autofocus>
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2" for="email">Email</label>
                                <input id="email" type="email"
                                    class="form-control form-control-solid @error('email') is-invalid @enderror"
                                    name="email" value="" required autocomplete="email">
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2" for="password">Mật khẩu</label>
                                <input id="password" type="password"
                                    class="form-control form-control-solid @error('password') is-invalid @enderror"
                                    name="password" required>
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
            Authorization: `Bearer ${token}`,
        };

        const $modalAddUser = $('#modal-add');
        const $results = $modalAddUser.find('.search-results');

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

        $modalAddUser.on('submit', 'form', function(e) {
            e.preventDefault();
            $(this).find('button[type="submit"]').attr('data-kt-indicator', "on");

            const body = {
                name: $modalAddUser.find('input[name="name"]').val(),
                email: $modalAddUser.find('input[name="email"]').val(),
                password: $modalAddUser.find('input[name="password"]').val(),
            }

            // console.log(body);

            axios.post("{{ route('api.user.register') }}", body, {
                    headers: headers
                })
                .then((res) => {
                    if (res.data.code == 0) {
                        // notify("Thêm mới nghiệp vụ thành công!", 'success');
                        $modalAddUser.modal('hide');
                        datatable.draw();
                        $modalAddUser.find('form')[0].reset();
                    } else {
                        notify(
                            res.data.data.join(", ") ??
                            "Có lỗi gì đó xảy ra! Vui lòng liên hệ để biết thêm chi tiết",
                            'error'
                        );
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
