<?php

namespace App\Http\Requests\Business;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BusinessRequest extends FormRequest
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
        $arr = explode('@', $this->route()->getActionName());
        $action = $arr[1];

        switch ($action) {
            case 'store':
                return [
                    'card_number' => 'required|digits:16|exists:cards,card_number',
                    'account_name' => 'nullable|string',
                    'name' => 'required|string',
                    'phone' => 'required|digits:10',
                    'fee_percent' => 'required|numeric',
                    'formality' => 'required|string',
                    'total_money' => 'required|numeric',
                ];

            case 'complete':
                return [
                    'id' => 'required|exists:businesses,id'
                ];

            case 'updateBusinessMoney':
                return [
                    'id' => 'required|exists:business_money,id',
                    'money' => 'nullable|numeric',
                    'note' => 'nullable|string',
                    'is_money_checked' => 'required|boolean',
                    'is_note_checked' => 'required|boolean',
                ];

            case 'updatePayExtra':
                return [
                    'id' => 'required|exists:businesses,id',
                    'pay_extra' => 'required|numeric'
                ];

            case 'viewMoney':
                return [
                    'id' => 'required|exists:businesses,id',
                ];

            case 'delete':
                return [
                    'id' => 'required|exists:businesses,id'
                ];
        }
    }

    public function messages()
    {
        return [
            'card_number.required' => 'Bạn vui lòng nhập số thẻ nhé!',
            'card_number.digits' => 'Số thẻ cần phải có đúng 16 chữ số!',
            'card_number.exists' => 'Số thẻ này không tồn tại trong hệ thống của chúng tôi.',
            'name.required' => 'Tên của bạn là gì? Bạn vui lòng nhập tên nhé!',
            'name.string' => 'Tên của bạn phải là chữ nhé!',
            'phone.required' => 'Bạn vui lòng nhập số điện thoại để chúng tôi có thể liên lạc.',
            'phone.digits' => 'Số điện thoại phải có 10 chữ số nhé!',
            'fee_percent.required' => 'Bạn vui lòng nhập phần trăm phí.',
            'fee_percent.numeric' => 'Phần trăm phí phải là số.',
            'formality.required' => 'Bạn vui lòng nhập hình thức.',
            'formality.string' => 'Hình thức phải là chuỗi ký tự.',
            'total_money.required' => 'Bạn vui lòng nhập tổng số tiền.',
            'total_money.numeric' => 'Tổng số tiền phải là số.',
            'id.required' => 'ID là bắt buộc, bạn vui lòng nhập nhé!',
            'id.exists' => 'ID này không tồn tại trong hệ thống của chúng tôi.',
            'note.required' => 'Bạn vui lòng nhập ghi chú.',
            'note.string' => 'Ghi chú phải là chuỗi ký tự.',
            'pay_extra.required' => 'Bạn vui lòng nhập số tiền trả thêm.',
            'pay_extra.numeric' => 'Số tiền trả thêm phải là số.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
