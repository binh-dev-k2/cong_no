{{--
    Variables passed from @include:
    - $values: Array of values
--}}
@php
$values = $values ?? [];
@endphp

@if(count($values) >= 2)
    <div class="mb-5 fv-row">
        <label class="required fs-6 fw-semibold mb-2">Khoảng nhỏ (VNĐ)</label>
        <input type="text"
               class="form-control money-input"
               min="0"
               value="{{ number_format($values[0]['value'] ?? 0, 0, '', ',') }}"
               placeholder="VD: 34.000.000"
               name="value"
               required />
    </div>
    <div class="mb-5 fv-row">
        <label class="required fs-6 fw-semibold mb-2">Khoảng lớn (VNĐ)</label>
        <input type="text"
               class="form-control money-input"
               min="0"
               value="{{ number_format($values[1]['value'] ?? 0, 0, '', ',') }}"
               placeholder="VD: 35.000.000"
               name="value"
               required />
    </div>
@else
    <div class="mb-5 fv-row">
        <label class="required fs-6 fw-semibold mb-2">Khoảng nhỏ (VNĐ)</label>
        <input type="text"
               class="form-control money-input"
               min="0"
               value=""
               placeholder="VD: 34.000.000"
               name="value"
               required />
    </div>
    <div class="mb-5 fv-row">
        <label class="required fs-6 fw-semibold mb-2">Khoảng lớn (VNĐ)</label>
        <input type="text"
               class="form-control money-input"
               min="0"
               value=""
               placeholder="VD: 35.000.000"
               name="value"
               required />
    </div>
@endif

<div class="form-text text-muted">
    <i class="bi bi-info-circle me-1"></i>
    Khoảng giá sẽ áp dụng cho các giao dịch có giá trị từ khoảng nhỏ đến khoảng lớn
</div>
