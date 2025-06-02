<?php

namespace App\Http\Requests\Agency;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AgencyBusinessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $arr = explode('@', $this->route()->getActionName());
        $action = $arr[1];

        switch ($action) {
            case 'storeAgencyBusiness':
                return [
                    'agency_id' => 'required|exists:agencies,id',
                    'machine_id' => 'required|exists:machines,id',
                    'total_money' => 'required|numeric|min:0',
                    'standard_code' => 'nullable|string|max:255',
                ];

            case 'updateAgencyBusiness':
                return [
                    'business_id' => 'required|exists:agency_businesses,id',
                    'machine_id' => 'required|exists:machines,id',
                    'total_money' => 'required|numeric|min:0',
                    'image_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'image_summary' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'standard_code' => 'nullable|string|max:255',
                ];

            case 'destroyAgencyBusiness':
                return [
                    'business_id' => 'required|exists:agency_businesses,id'
                ];

            case 'completeAgencyBusiness':
                return [
                    'business_id' => 'required|exists:agency_businesses,id'
                ];

            default:
                return [];
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'agency_id.required' => 'Vui lòng chọn đại lý',
            'agency_id.exists' => 'Đại lý không tồn tại',
            'machine_id.required' => 'Vui lòng chọn máy',
            'machine_id.exists' => 'Máy không tồn tại',
            'total_money.required' => 'Vui lòng nhập tổng số tiền',
            'total_money.numeric' => 'Tổng số tiền phải là số',
            'total_money.min' => 'Tổng số tiền phải lớn hơn 0',
            'image_front.image' => 'Ảnh mặt trước phải là file hình ảnh',
            'image_front.mimes' => 'Ảnh mặt trước phải có định dạng: jpeg, png, jpg, gif',
            'image_front.max' => 'Ảnh mặt trước không được vượt quá 2MB',
            'image_summary.image' => 'Ảnh tổng kết phải là file hình ảnh',
            'image_summary.mimes' => 'Ảnh tổng kết phải có định dạng: jpeg, png, jpg, gif',
            'image_summary.max' => 'Ảnh tổng kết không được vượt quá 2MB',
            'standard_code.max' => 'Mã chuẩn chi không được vượt quá 255 ký tự',
            'business_id.required' => 'ID nghiệp vụ là bắt buộc',
            'business_id.exists' => 'Nghiệp vụ không tồn tại',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'agency_id' => 'đại lý',
            'machine_id' => 'máy',
            'total_money' => 'tổng tiền',
            'standard_code' => 'mã chuẩn',
            'image_front' => 'ảnh mặt trước',
            'image_summary' => 'ảnh tóm tắt',
            'is_completed' => 'trạng thái hoàn thành'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert string boolean to actual boolean
        if ($this->has('is_completed')) {
            $this->merge([
                'is_completed' => $this->boolean('is_completed')
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'code' => 1,
                'data' => $validator->errors()->all()
            ], 422)
        );
    }
}
