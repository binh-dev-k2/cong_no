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
                    'image_front' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'image_summary' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'standard_code' => 'required|string|max:255',
                ];

            case 'updateAgencyBusiness':
                return [
                    'business_id' => 'required|exists:agency_businessess,id',
                    'machine_id' => 'required|exists:machines,id',
                    'total_money' => 'required|numeric|min:0',
                    'image_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'image_summary' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'standard_code' => 'required|string|max:255',
                ];

            case 'destroyAgencyBusiness':
                return [
                    'business_id' => 'required|exists:agency_businessess,id'
                ];

            default:
                return [];
        }
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'agency_id.required' => 'Bạn vui lòng chọn đại lý cho nghiệp vụ này nhé!',
            'agency_id.exists' => 'Đại lý này không tồn tại trong hệ thống của chúng tôi.',
            'machine_id.required' => 'Bạn vui lòng chọn máy cho nghiệp vụ này nhé!',
            'machine_id.exists' => 'Máy này không tồn tại trong hệ thống của chúng tôi.',
            'total_money.required' => 'Bạn vui lòng nhập tổng số tiền cho nghiệp vụ.',
            'total_money.numeric' => 'Tổng số tiền phải là số nhé!',
            'total_money.min' => 'Tổng số tiền không được nhỏ hơn 0.',
            'standard_code.required' => 'Bạn vui lòng nhập mã chuẩn cho nghiệp vụ.',
            'standard_code.string' => 'Mã chuẩn phải là chuỗi ký tự.',
            'standard_code.max' => 'Mã chuẩn không được vượt quá 255 ký tự.',
            'image_front.image' => 'Ảnh mặt trước phải là hình ảnh.',
            'image_front.mimes' => 'Ảnh mặt trước chỉ được phép là các định dạng: jpeg, png, jpg, gif.',
            'image_front.max' => 'Ảnh mặt trước không được vượt quá 2MB.',
            'image_summary.image' => 'Ảnh tóm tắt phải là hình ảnh.',
            'image_summary.mimes' => 'Ảnh tóm tắt chỉ được phép là các định dạng: jpeg, png, jpg, gif.',
            'image_summary.max' => 'Ảnh tóm tắt không được vượt quá 2MB.',
            'business_id.required' => 'ID nghiệp vụ là bắt buộc, bạn vui lòng nhập nhé!',
            'business_id.exists' => 'Nghiệp vụ này không tồn tại trong hệ thống của chúng tôi.',
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

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
