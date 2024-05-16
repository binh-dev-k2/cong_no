document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = document.querySelectorAll('');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var row = this.closest('tr');
            if (this.checked) {
                row.classList.replace('enable-row', 'disable-row');
            } else {
                row.classList.replace('disable-row', 'enable-row');
            }
        });
    });
});
