<!-- Thông báo popup -->
<div class="modal fade" id="announcement-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <!-- Chỉ hiển thị nút đóng tinh tế -->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body pt-0 pb-5 px-10">
                <!--begin::Heading-->
                <div class="mb-10 text-center">
                    <h1 class="mb-3">Cập nhật hệ thống</h1>
                    <div class="text-muted fw-semibold fs-5">
                        Vui lòng xem thông tin quan trọng về phiên bản mới
                    </div>
                </div>
                <!--end::Heading-->

                <!--begin::Content-->
                <div class="d-flex flex-column">
                    <!--begin::Alert-->
                    <div class="alert alert-dismissible bg-light-warning d-flex flex-column flex-sm-row p-5 mb-10">
                        <i class="ki-duotone ki-notification-status fs-2hx text-warning me-4 mb-5 mb-sm-0">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-warning">Vui lòng kiểm tra và cập nhật lại % phí thẻ tại máy</h4>
                            <span>Vui lòng cập nhật trước khi thao tác tại các phần khác.</span>
                        </div>
                    </div>
                    <!--end::Alert-->

                    <!--begin::List-->
                    <div class="fs-5 fw-semibold mb-6">
                        <span class="text-gray-800">Các thay đổi trong phiên bản này:</span>
                        <ul class="py-2">
                            <li class="py-2">
                                <span class="fw-bold">Thêm cột phí thẻ mới</span> - Đã được thêm cột phí AMEX, NAPAS, VISA, JCB, MASTERCARD
                            </li>
                            <li class="py-2">
                                <span class="fw-bold">Giao diện được cải thiện</span> - Các thành phần hiển thị đã được cập nhật
                            </li>
                        </ul>
                    </div>
                    <!--end::List-->

                </div>
                <!--end::Content-->
            </div>
            <div class="modal-footer flex-center">
                <button type="button" id="dont-show-again" class="btn btn-light me-3">
                    <i class="ki-duotone ki-eye-slash fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Không hiển thị lại
                </button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-check fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Đã hiểu
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script kiểm tra ngày và hiển thị thông báo -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kiểm tra ngày hiện tại có trước 01/06/2025 không
        const targetDate = new Date('2025-06-01T00:00:00');
        const currentDate = new Date();

        // Kiểm tra localStorage xem đã ẩn thông báo chưa
        const announcementHidden = localStorage.getItem('announcement_hidden');

        // Chỉ hiển thị nếu ngày hiện tại trước ngày mục tiêu và chưa ẩn thông báo
        if (currentDate < targetDate && !announcementHidden) {
            // Thêm hiệu ứng fade-in cho modal
            setTimeout(function() {
                const announcementModal = new bootstrap.Modal(document.getElementById('announcement-modal'));
                announcementModal.show();
            }, 500);

            // Xử lý sự kiện khi nhấn "Không hiển thị lại"
            document.getElementById('dont-show-again').addEventListener('click', function() {
                localStorage.setItem('announcement_hidden', 'true');
                bootstrap.Modal.getInstance(document.getElementById('announcement-modal')).hide();
            });
        }
    });
</script>
