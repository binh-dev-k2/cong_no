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
            <form action="#" method="post" id="form-role">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm mới người dùng</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" style="max-height: calc(100vh - 30rem)">
                        <div class="content">
                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2" for="name">Tên vai trò</label>
                                <input id="name" type="text" class="form-control form-control-solid"
                                    name="name" value="" required autofocus>
                            </div>

                            <div class="d-flex flex-column mb-3 fv-row">
                                <label class="fs-6 fw-semibold mb-2" for="permissions">Quyền hạn</label>
                                <div class="form-check d-flex flex-wrap gap-3">
                                    <?php
                                    $prevPermission = '';
                                    ?>
                                    @foreach ($permissions as $permission)
                                        <?php
                                        $currentPermissionName = explode('-', $permission->name);
                                        $currentPermission = $currentPermissionName[0];
                                        ?>
                                        @if ($prevPermission != $currentPermission)
                                            <?php
                                            $prevPermission = $currentPermission;
                                            ?>
                                            <h3 class="fw-bold w-100">{{ __("permissions.{$currentPermissionName[0]}") }}</h3>
                                        @endif
                                        <div class="form-check form-check-inline me-3">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                value="{{ $permission->name }}">
                                            <label class="form-check-label"
                                                for="permissions">{{ isset($currentPermissionName[1]) ? ucfirst(__("permissions.{$currentPermissionName[1]}")) : __("permissions.{$currentPermissionName[0]}") }}</label>
                                        </div>
                                    @endforeach
                                </div>
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
