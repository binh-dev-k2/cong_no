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
            case 'complete':
                return [
                    'id' => 'required|exists:cards,id'
                ];

            case 'updateNote':
                return [
                    'id' => 'required|exists:card_histories,id',
                    'note' => 'required|string'
                ];

            case 'updatePayExtra':
                return [
                    'id' => 'required|exists:cards,id',
                    'pay_extra' => 'required|numeric'
                ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
