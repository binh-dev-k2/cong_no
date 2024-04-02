<?php

namespace App\Http\Requests\Card;

use Illuminate\Foundation\Http\FormRequest;

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
            'card_number' => 'required|numeric|digits:16|unique:card,card_number',
            'account_number' => 'required|numeric|unique:card,account_number',
            'date_due' => 'required|date_format:Y-m-d',
            'date_return' => 'required|date_format:Y-m-d',
            'login_info' => 'required|string',
            'bank_id'=> 'required|string|exists:banks,code'
        ];
    }
    function messages()
    {
        return [
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
            'card_number.unique' => 'Số thẻ ngân hàng đã tồn tại trong hệ thống.',
            'account_number.unique' => 'Số tài khoản ngân hàng đã tồn tại trong hệ thống.',
            'bank_id.exists' => 'Ngân hàng không hợp lệ.',
            'bank_id.required' => 'Ngân hàng  là bắt buộc.'
        ];
    }
}
