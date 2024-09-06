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
            'id' => 'required|numeric|exists:cards,id',
            'card_number' => 'required|numeric|digits:16',
            'account_number' => 'nullable|numeric',
            'date_due' => 'nullable|numeric|min:0|max:31',
            'date_return' => 'nullable|date_format:Y-m-d',
            'login_info' => 'nullable|string',
            'bank_code' => 'required|string|exists:banks,code',
            'account_name' => 'nullable|string',
            'note' => 'nullable|string|max:255',
            'fee_percent' => 'required|numeric',
        ];
    }
    function messages()
    {
        return [
            // 'id.required' => 'Vui lòng nhập ID của thẻ.',
            'id.exists' => 'ID của thẻ không tồn tại.',
            'card_number.required' => 'Số thẻ ngân hàng là bắt buộc.',
            'card_number.numeric' => 'Số thẻ ngân hàng phải là số.',
            'card_number.digits' => 'Số thẻ ngân hàng phải gồm 16 chữ số.',
            'account_number.numeric' => 'Số tài khoản ngân hàng phải là số.',
            'date_due.required' => 'Ngày đáo hạn là bắt buộc.',
            'date_due.numeric' => 'Ngày đáo hạn phải là số và nằm trong khoảng từ 0 đến 31.',
            //            'date_return.required' => 'Ngày trả là bắt buộc.',
            'date_return.date' => 'Ngày trả không hợp lệ.',
            'login_info.string' => 'Thông tin đăng nhập phải là chuỗi ký tự.',
            'bank_code.exists' => 'Ngân hàng không hợp lệ.',
            'bank_code.required' => 'Ngân hàng  là bắt buộc.',
            'fee_percent.required' => 'Phần trăm phí là bắt buộc.',
            'fee_percent.numeric' => 'Phần trăm phí phải là số.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
