<?php

namespace App\Http\Requests\Card;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RemindCardRequest extends FormRequest
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
            'id' => 'required|exists:cards,id',
            'customer_id' => 'required|exists:customers,id'
        ];
    }

    public function messages()
    {
        return [
            // 'id.required' => 'Vui lòng nhập ID của thẻ.',
            'id.exists' => 'ID của thẻ không tồn tại.',
            'customer_id.required' => 'Vui lòng nhập ID của khách hàng.',
            'customer_id.exists' => 'ID của khách hàng không tồn tại.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
