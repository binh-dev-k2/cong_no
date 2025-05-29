{{--
    Variables passed from @include:
    - $values: Array of values
--}}
@php
$values = $values ?? [];
@endphp

@if(count($values) > 0)
    @foreach($values as $value)
        <div class="mb-5 fv-row percent-value-item">
            <label class="required fs-6 fw-semibold mb-2">Giá trị phần trăm</label>
            <div class="input-group">
                <input type="text"
                       class="form-control money-input"
                       min="0"
                       value="{{ number_format($value['value'] ?? 0, 0, '', ',') }}"
                       placeholder="VD: 10"
                       name="value"
                       required />
                <span class="input-group-text">%</span>
                <button type="button" class="btn btn-outline-danger delete-business-percent">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    @endforeach
@else
    <div class="mb-5 fv-row percent-value-item">
        <label class="required fs-6 fw-semibold mb-2">Giá trị phần trăm</label>
        <div class="input-group">
            <input type="text"
                   class="form-control money-input"
                   min="0"
                   value=""
                   placeholder="VD: 10"
                   name="value"
                   required />
            <span class="input-group-text">%</span>
            <button type="button" class="btn btn-outline-danger delete-business-percent">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
@endif

<div class="text-center mt-5">
    <button type="button" class="btn btn-light-primary btn-sm add-business-percent">
        <i class="bi bi-plus-circle me-2"></i>Thêm % phí
    </button>
</div>

<div class="form-text text-muted mt-3">
    <i class="bi bi-info-circle me-1"></i>
    Bạn có thể thêm nhiều mức phần trăm phí khác nhau. Hệ thống sẽ áp dụng theo thứ tự ưu tiên.
</div>
