<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
        return [
            'id' => 'required|exists:customers,id',
            'customer_name' => 'required|string',
            'customer_phone' => [
                'required',
                'numeric',
                'digits:10',
                'unique:customers,phone,' . $this->id . ',id',
                function ($attribute, $value, $fail) {
                    if (strlen($value) != 10) {
                        $fail('Số điện thoại khách hàng phải gồm 10 chữ số.');
                    }
                    if (substr($value, 0, 1) != '0') {
                        $fail('Số điện thoại khách hàng phải bắt đầu bằng số 0.');
                    }
                },
            ],
            // 'fee_percent' => 'required|numeric',
            'card_ids' => 'required|array|exists:cards,id'
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
            'fee_percent.required' => 'Phần trăm phí là bắt buộc.',
            'fee_percent.numeric' => 'Phần trăm phí phải là số.',
            'card_ids.required' => 'Danh sách thẻ ngân hàng là bắt buộc.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
