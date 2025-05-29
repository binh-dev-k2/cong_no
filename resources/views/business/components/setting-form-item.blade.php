{{--
    Variables passed from @include:
    - $index: Setting index
    - $type: Setting type (MONEY/PERCENT)
    - $key: Setting key
    - $values: Array of values
    - $isExisting: Whether this is an existing setting (default: true)
--}}
@php
$isExisting = $isExisting ?? true;
@endphp

<div class="card shadow-sm mb-7 business-setting-container" data-index="{{ $index }}">
    <div class="card-header border-0 pt-6 pb-2">
        <div class="card-title">
            <span class="badge {{ $type === 'MONEY' ? 'badge-light-success' : 'badge-light-info' }} setting-type-badge">
                <i class="bi {{ $type === 'MONEY' ? 'bi-cash-coin' : 'bi-percent' }} me-1"></i>
                {{ $type === 'MONEY' ? 'Khoảng giá' : 'Phần trăm' }}
            </span>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-sm btn-icon btn-color-danger btn-active-light-danger delete-business-setting">
                <i class="bi bi-x-lg fs-2"></i>
            </button>
        </div>
    </div>
    <div class="card-body pt-0">
        {{-- Type Selection --}}
        <div class="mb-7 fv-row">
            <label class="required fs-6 fw-semibold mb-2">Loại cài đặt</label>
            <div class="d-flex gap-5">
                <div class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input type-radio"
                           type="radio"
                           value="MONEY"
                           name="type[{{ $index }}][]"
                           {{ $type === 'MONEY' ? 'checked' : '' }}
                           {{ $isExisting ? 'disabled' : '' }}
                           required />
                    <label class="form-check-label">Khoảng giá</label>
                </div>
                <div class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input type-radio"
                           type="radio"
                           value="PERCENT"
                           name="type[{{ $index }}][]"
                           {{ $type === 'PERCENT' ? 'checked' : '' }}
                           {{ $isExisting ? 'disabled' : '' }}
                           required />
                    <label class="form-check-label">% phí</label>
                </div>
            </div>
            @if($isExisting)
                <div class="form-text">
                    <i class="bi bi-info-circle me-1"></i>
                    Không thể thay đổi loại cài đặt đã tồn tại
                </div>
            @endif
        </div>

        {{-- Key Input --}}
        <div class="mb-7 fv-row">
            <label class="required fs-6 fw-semibold mb-2">Mốc (Triệu VNĐ)</label>
            <input type="number"
                   class="form-control form-control-solid"
                   value="{{ $key }}"
                   name="key"
                   placeholder="Nhập mốc tiền (triệu)"
                   required>
            <div class="form-text">
                Ví dụ: 50 (tương đương 50 triệu VNĐ)
            </div>
        </div>

        {{-- Value Container --}}
        <div class="container-value">
            @if($type === 'MONEY')
                @include('business.components.setting-money-values', ['values' => $values])
            @else
                @include('business.components.setting-percent-values', ['values' => $values])
            @endif
        </div>
    </div>
</div>
