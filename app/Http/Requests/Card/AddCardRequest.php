<?php

namespace App\Http\Requests\Card;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddCardRequest extends FormRequest
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
            'card_number' => 'required|numeric|digits:16|unique:cards,card_number',
            'account_number' => 'required|numeric|unique:cards,account_number',
            'date_due' => 'required|numeric|min:1|max:30',
//            'date_return' => 'required|date_format:Y-m-d',
            'date_return' => 'nullable|date_format:Y-m-d',
//            'login_info' => 'required|string',
            'login_info' => 'nullable',
            'bank_code' => 'required|string|exists:banks,code',
            'account_name' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'card_number.required' => 'Số thẻ ngân hàng là bắt buộc.',
            'card_number.numeric' => 'Số thẻ ngân hàng phải là số.',
            'card_number.digits' => 'Số thẻ ngân hàng phải gồm 16 chữ số.',
            'account_number.numeric' => 'Số tài khoản ngân hàng phải là số.',
            'date_due.required' => 'Ngày đáo hạn là bắt buộc.',
            'date_due.numeric' => 'Ngày đáo hạn không hợp lệ.',
            'date_return.required' => 'Ngày trả là bắt buộc.',
            'date_return.date' => 'Ngày trả không hợp lệ.',
            'login_info.string' => 'Thông tin đăng nhập phải là chuỗi ký tự.',
            'card_number.unique' => 'Số thẻ ngân hàng đã tồn tại trong hệ thống.',
            'bank_code.exists' => 'Ngân hàng không hợp lệ.',
            'bank_code.required' => 'Ngân hàng  là bắt buộc.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
