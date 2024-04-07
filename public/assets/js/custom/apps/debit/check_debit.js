document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = document.querySelectorAll('.form-check-input');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var row = this.closest('tr'); // Lấy hàng chứa checkbox
            if (this.checked) {
                row.classList.replace('enable-row', 'disable-row'); // Thêm class 'checked' cho hàng
            } else {
                row.classList.replace('disable-row', 'enable-row'); // Xóa class 'checked' cho hàng
            }
        });
    });
});
