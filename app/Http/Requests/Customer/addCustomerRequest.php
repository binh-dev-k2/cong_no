<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class addCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_name' => 'required|string',
            'customer_phone' => 'required|numeric|unique:customers,customer_phone',
        ];
    }

    function messages()
    {
        return [
            'customer_name.required' => 'Tên khách hàng là bắt buộc.',
            'customer_name.string' => 'Tên khách hàng phải là chuỗi ký tự.',
            'customer_phone.required' => 'Số điện thoại khách hàng là bắt buộc.',
            'customer_phone.numeric' => 'Số điện thoại khách hàng phải là số.',
            'customer_phone.unique' => 'Số điện thoại khách hàng đã tồn tại trong hệ thống.',
        ];
    }
}
