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
                    'card_number' => 'required|digits:16',
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

            case 'updateMoneyNote':
                return [
                    'id' => 'required|exists:business_money,id',
                    'note' => 'required|string'
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
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
