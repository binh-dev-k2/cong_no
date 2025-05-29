@php
use App\Services\BusinessSettingService;

$businessSettingService = app(BusinessSettingService::class);
$settingsData = $businessSettingService->getSettingsGrouped();
$businessSettingMoney = $settingsData['money'];
$businessSettingPercent = $settingsData['percent'];
$count = $settingsData['count'];
$index = 0;
@endphp

{{-- Minimal Custom Styles --}}
<style>
.business-setting-container {
    transition: all 0.3s ease;
}

.business-setting-container:hover {
    transform: translateY(-2px);
}

.business-setting-container.adding {
    animation: slideInUp 0.4s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.money-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15) !important;
}
</style>

<div class="modal-content">
    <form action="#" id="form-edit-setting">
        <div class="modal-header bg-success">
            <h4 class="modal-title text-white">
                <i class="bi bi-gear-wide-connected me-2"></i>
                Cấu hình tiền chia
            </h4>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body bg-light">
            <div class="scroll-y me-n7 pe-7" style="max-height: calc(100vh - 25rem)">

                {{-- Money Settings Section --}}
                @if(isset($businessSettingMoney) && $businessSettingMoney->count() > 0)
                    <div class="text-center mb-5">
                        <span class="badge bg-success fs-7 fw-bold text-white">
                            <i class="bi bi-cash-coin me-1"></i>
                            Cài đặt khoảng giá
                        </span>
                                    </div>

                    @foreach ($businessSettingMoney as $key => $businessSetting)
                        @include('business.components.setting-form-item', [
                            'index' => $index,
                            'type' => 'MONEY',
                            'key' => $key,
                            'values' => $businessSetting->toArray(),
                            'isExisting' => true
                        ])
                        @php $index++; @endphp
                        @endforeach

                    <div class="separator border-success my-8"></div>
                @endif

                {{-- Percent Settings Section --}}
                @if(isset($businessSettingPercent) && $businessSettingPercent->count() > 0)
                    <div class="text-center mb-5">
                        <span class="badge bg-warning fs-7 fw-bold text-dark">
                            <i class="bi bi-percent me-1"></i>
                            Cài đặt phần trăm phí
                        </span>
                                    </div>

                    @foreach ($businessSettingPercent as $key => $businessSetting)
                        @include('business.components.setting-form-item', [
                            'index' => $index,
                            'type' => 'PERCENT',
                            'key' => $key,
                            'values' => $businessSetting->toArray(),
                            'isExisting' => true
                        ])
                        @php $index++; @endphp
                                        @endforeach

                    <div class="separator border-success my-8"></div>
                @endif

                {{-- Empty State --}}
                @if((!isset($businessSettingMoney) || $businessSettingMoney->count() == 0) &&
                    (!isset($businessSettingPercent) || $businessSettingPercent->count() == 0))
                    <div class="text-center py-15">
                        <i class="bi bi-gear-wide-connected fs-3x text-muted mb-5"></i>
                        <h5 class="text-gray-700 fw-bold mb-3">Chưa có cài đặt nào</h5>
                        <p class="text-muted mb-5">Hãy tạo cài đặt đầu tiên để bắt đầu quản lý tiền chia hiệu quả!</p>
                    </div>
                @endif

                {{-- Add New Setting Button --}}
                <div class="text-center">
                    <button type="button" class="btn btn-light-success add-business-setting">
                        <i class="bi bi-plus-circle me-2"></i>
                        Thêm cài đặt mới
                    </button>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-2"></i>Hủy bỏ
            </button>
            <button type="submit" class="btn btn-success">
                <span class="indicator-label">
                    <i class="bi bi-check-circle me-2"></i>Lưu thay đổi
                </span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                    Đang xử lý...
                </span>
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
    /**
     * Business Setting Modal Handler
     * Using theme classes for consistency
     */
    class BusinessSettingModal {
        constructor(options = {}) {
            this.modalId = options.modalId || '#modal-edit-setting';
            this.formId = options.formId || '#form-edit-setting';
            this.apiUrl = options.apiUrl || '';
            this.token = options.token || '';
            this.count = options.count || 0;

            this.init();
        }

        init() {
            this.bindEvents();
            this.initializeMoneyInputs();
            this.addSettingBadges();
        }

        addSettingBadges() {
            // Add type badges to existing settings
            $('.business-setting-container').each(function() {
                const $container = $(this);
                const type = $container.find('.type-radio:checked').val();
                const badgeClass = type === 'MONEY' ? 'bg-success text-white' : 'bg-warning text-dark';
                const icon = type === 'MONEY' ? 'bi-cash-coin' : 'bi-percent';
                const text = type === 'MONEY' ? 'Khoảng giá' : 'Phần trăm';

                if (!$container.find('.setting-type-badge').length) {
                    $container.find('.card-header').prepend(`
                        <span class="badge ${badgeClass} setting-type-badge">
                            <i class="bi ${icon} me-1"></i>${text}
                        </span>
                    `);
                }
            });
        }

        bindEvents() {
            // Form submission
            $(document).off('submit', this.formId).on('submit', this.formId, (e) => {
                this.handleFormSubmit(e);
            });

            // Delete business setting
            $(document).off('click', '.delete-business-setting').on('click', '.delete-business-setting', (e) => {
                this.handleDeleteSetting(e);
            });

            // Delete percent value
            $(document).off('click', '.delete-business-percent').on('click', '.delete-business-percent', (e) => {
                this.handleDeletePercent(e);
            });

            // Add new business setting
            $(document).off('click', '.add-business-setting').on('click', '.add-business-setting', (e) => {
                this.handleAddSetting(e);
            });

            // Add percent value
            $(document).off('click', '.add-business-percent').on('click', '.add-business-percent', (e) => {
                this.handleAddPercent(e);
            });

            // Type radio change (only for new settings)
            $(document).off('change', '.type-radio:not(:disabled)').on('change', '.type-radio:not(:disabled)', (e) => {
                this.handleTypeChange(e);
            });

            // Money input formatting
            $(document).off('input', '.money-input').on('input', '.money-input', (e) => {
                this.formatMoneyInput(e);
            });
        }

        handleFormSubmit(e) {
            e.preventDefault();

            const data = this.collectFormData();
            if (data.length === 0) {
                this.showNotification("Vui lòng nhập thông tin cài đặt", 'error');
                return;
            }

            this.submitForm(data);
        }

        collectFormData() {
            const data = [];
            $(this.modalId).find('.business-setting-container').each(function() {
                const $container = $(this);
                const type = $container.find('.type-radio:checked').val();
                const key = parseInt($container.find('input[name="key"]').val());

                $container.find('input[name="value"]').each(function() {
                    const value = $(this).val().replace(/[,]/g, '');
                    if (value && !isNaN(value)) {
                        data.push({ type, key, value: parseInt(value) });
                    }
                });
            });

            return data;
        }

        async submitForm(data) {
            const $submitButton = $(this.formId).find('button[type="submit"]');
            this.setLoadingState($submitButton, true);

            try {
                const response = await axios.post(this.apiUrl, data, {
                    headers: { Authorization: this.token }
                });

                if (response.data.code === 0) {
                    await this.showNotification('Cập nhật thành công!', 'success');
                    $(this.modalId).modal('hide');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                    const errors = Array.isArray(response.data.data)
                        ? response.data.data.join(', ')
                        : response.data.message || 'Có lỗi xảy ra';
                    this.showNotification(errors, 'error');
                }
            } catch (error) {
                console.error('Submit error:', error);
                let errorMessage = 'Có lỗi xảy ra khi cập nhật';

                if (error.response?.data?.data) {
                    errorMessage = Array.isArray(error.response.data.data)
                        ? error.response.data.data.join(', ')
                        : error.response.data.data;
                } else if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                } else if (error.message) {
                    errorMessage = error.message;
                }

                this.showNotification(errorMessage, 'error');
            } finally {
                this.setLoadingState($submitButton, false);
            }
        }

        handleDeleteSetting(e) {
            e.preventDefault();
            const $container = $(e.target).closest('.business-setting-container');

            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa cài đặt này không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa ngay',
                cancelButtonText: 'Hủy bỏ',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-light'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $container.fadeOut(300, function() {
                        $(this).remove();
                    });
                    this.showNotification('Đã xóa cài đặt thành công', 'success');
                }
            });
        }

        handleDeletePercent(e) {
            e.preventDefault();
            const $container = $(e.target).closest('.container-value');
            const $item = $(e.target).closest('.percent-value-item');

            if ($container.find('.percent-value-item').length > 1) {
                $item.fadeOut(200, function() {
                    $(this).remove();
                });
            } else {
                this.showNotification('Phải có ít nhất một giá trị phần trăm', 'warning');
            }
        }

        handleAddSetting(e) {
            e.preventDefault();
            const template = this.getSettingTemplate();
            const $newElement = $(template);

            $(this.formId).find('.add-business-setting').parent().before($newElement);
            $newElement.addClass('adding');

            this.count++;
            this.initializeMoneyInputs();
            this.addSettingBadges();

            // Scroll to new element
            $newElement[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        handleAddPercent(e) {
            e.preventDefault();
            const template = this.getPercentTemplate();
            const $newElement = $(template);

            $(e.target).before($newElement);
            $newElement.hide().fadeIn(300);
        }

        handleTypeChange(e) {
            const $container = $(e.target).closest('.card-body').find('.container-value');
            const type = $(e.target).val();

            $container.fadeOut(200, () => {
                if (type === 'PERCENT') {
                    $container.html(this.getPercentValuesTemplate());
                } else {
                    $container.html(this.getMoneyValuesTemplate());
                }
                $container.fadeIn(300);
                this.initializeMoneyInputs();
            });

            // Update badge
            const $badge = $(e.target).closest('.business-setting-container').find('.setting-type-badge');
            const badgeClass = type === 'MONEY' ? 'bg-success text-white' : 'bg-warning text-dark';
            const icon = type === 'MONEY' ? 'bi-cash-coin' : 'bi-percent';
            const text = type === 'MONEY' ? 'Khoảng giá' : 'Phần trăm';

            $badge.attr('class', `badge ${badgeClass} setting-type-badge`)
                  .html(`<i class="bi ${icon} me-1"></i>${text}`);
        }

        formatMoneyInput(e) {
            const input = e.target;
            let value = input.value.replace(/[^0-9]/g, '');

            if (value) {
                value = parseInt(value).toLocaleString('vi-VN');
            }

            input.value = value;
        }

        initializeMoneyInputs() {
            $('.money-input').each((index, input) => {
                if (input.value && !input.value.includes(',')) {
                    const numValue = parseInt(input.value);
                    if (!isNaN(numValue)) {
                        input.value = numValue.toLocaleString('vi-VN');
                    }
                }
            });
        }

        getSettingTemplate() {
            return `
                <div class="card shadow-sm mb-7 business-setting-container" data-index="${this.count}">
                    <div class="card-header border-0 pt-6 pb-2">
                        <div class="card-title">
                            <span class="badge bg-success text-white setting-type-badge">
                                <i class="bi bi-cash-coin me-1"></i>Khoảng giá
                            </span>
                        </div>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-sm btn-icon btn-color-danger btn-active-light-danger delete-business-setting">
                                <i class="bi bi-x-lg fs-2"></i>
                                    </button>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-7 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Loại cài đặt</label>
                            <div class="d-flex gap-5">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input type-radio" type="radio" value="MONEY" name="type[${this.count}][]" checked required />
                                    <label class="form-check-label">Khoảng giá</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input type-radio" type="radio" value="PERCENT" name="type[${this.count}][]" required />
                                    <label class="form-check-label">% phí</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-7 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Mốc (Triệu VNĐ)</label>
                            <input type="number" class="form-control form-control-solid" value="" name="key" placeholder="Nhập mốc tiền (triệu)" required>
                        </div>
                        <div class="container-value">
                            ${this.getMoneyValuesTemplate()}
                        </div>
                    </div>
                </div>
            `;
        }

        getPercentTemplate() {
            return `
                <div class="mb-5 fv-row percent-value-item">
                    <label class="required fs-6 fw-semibold mb-2">Giá trị phần trăm</label>
                    <div class="input-group">
                        <input type="text" class="form-control money-input" min="0" value="" placeholder="VD: 10" name="value" required />
                        <span class="input-group-text">%</span>
                        <button type="button" class="btn btn-outline-danger delete-business-percent">
                            <i class="bi bi-x-lg"></i>
                    </button>
                    </div>
                </div>
            `;
        }

        getMoneyValuesTemplate() {
            return `
                <div class="mb-5 fv-row">
                    <label class="required fs-6 fw-semibold mb-2">Khoảng nhỏ (VNĐ)</label>
                    <input type="text" class="form-control money-input" min="0" value="" placeholder="VD: 34.000.000" name="value" required />
                </div>
                <div class="mb-5 fv-row">
                    <label class="required fs-6 fw-semibold mb-2">Khoảng lớn (VNĐ)</label>
                    <input type="text" class="form-control money-input" min="0" value="" placeholder="VD: 35.000.000" name="value" required />
                </div>
            `;
        }

        getPercentValuesTemplate() {
            return `
                <div class="mb-5 fv-row percent-value-item">
                    <label class="required fs-6 fw-semibold mb-2">Giá trị phần trăm</label>
                    <div class="input-group">
                        <input type="text" class="form-control money-input" min="0" value="" placeholder="VD: 10" name="value" required />
                        <span class="input-group-text">%</span>
                        <button type="button" class="btn btn-outline-danger delete-business-percent">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button type="button" class="btn btn-light-success btn-sm add-business-percent">
                        <i class="bi bi-plus-circle me-2"></i>Thêm % phí
                    </button>
                </div>
            `;
        }

        setLoadingState($button, isLoading) {
            if (isLoading) {
                $button.attr('data-kt-indicator', 'on').prop('disabled', true);
            } else {
                $button.attr('data-kt-indicator', 'off').prop('disabled', false);
            }
        }

        resetForm() {
            $(this.modalId).find('form')[0].reset();
        }

        showNotification(text, type = 'success', showCancelButton = false) {
            return Swal.fire({
                text: text,
                icon: type,
                buttonsStyling: false,
                showCancelButton: showCancelButton,
                confirmButtonText: "Đã hiểu",
                cancelButtonText: "Đóng",
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-light"
                },
                timer: type === 'success' ? 3000 : undefined,
                timerProgressBar: true
            });
        }
    }

    // Initialize the modal handler
    const businessSettingModal = new BusinessSettingModal({
        modalId: '#modal-edit-setting',
        formId: '#form-edit-setting',
        apiUrl: "{{ route('api.business.updateSetting') }}",
        token: window.token || '',
        count: {{ $count }}
    });
});
</script>
