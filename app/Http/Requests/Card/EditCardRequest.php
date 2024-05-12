<?php

namespace App\Http\Requests\Card;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditCardRequest extends FormRequest
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
            'id' => 'required|numeric',
            'card_number' => 'required|numeric|digits:16',
            'account_number' => 'required|numeric',
            'date_due' => 'required|date_format:Y-m-d',
            'date_return' => 'required|date_format:Y-m-d',
            'login_info' => 'required|string',
            'bank_code' => 'required|string',
            'account_name' => 'required|string',
            'fee_percent' => 'required|numeric',
            'total_money' => 'required|numeric',
            'formality' => "required|string",
            'pay_extra' => "required|numeric",
            'note' => 'nullable|string|max:255',

        ];
    }
    function messages()
    {
        return [
            'id' => 'required|numeric',
            'card_number.required' => 'Số thẻ ngân hàng là bắt buộc.',
            'card_number.numeric' => 'Số thẻ ngân hàng phải là số.',
            'card_number.digits' => 'Số thẻ ngân hàng phải gồm 16 chữ số.',
            'account_number.required' => 'Số tài khoản ngân hàng là bắt buộc.',
            'account_number.numeric' => 'Số tài khoản ngân hàng phải là số.',
            'date_due.required' => 'Ngày đáo hạn là bắt buộc.',
            'date_due.date' => 'Ngày đáo hạn không hợp lệ.',
            'date_return.required' => 'Ngày trả là bắt buộc.',
            'date_return.date' => 'Ngày trả không hợp lệ.',
            'login_info.required' => 'Thông tin đăng nhập là bắt buộc.',
            'login_info.string' => 'Thông tin đăng nhập phải là chuỗi ký tự.',
            'bank_code.exists' => 'Ngân hàng không hợp lệ.',
            'bank_code.required' => 'Ngân hàng  là bắt buộc.',
            'fee_percent.required' => 'Phần trăm phí là bắt buộc.',
            'fee_percent.numeric' => 'Phần trăm phí phải là số.',
            'total_money.required' => 'Số tiền là bắt buộc.',
            'total_money.numeric' => 'Số tiền phải là số.',
            'formality.required' => 'Hình thức thanh toán là bắt buộc.',
            'pay_extra.required' => 'Phí thanh toán là bắt buộc.',
            'pay_extra.numeric' => 'Phí thanh toán phải là số.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
