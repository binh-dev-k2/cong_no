<?php

namespace App\Http\Requests\Collaborator;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CollaboratorRequest extends FormRequest
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
        $action = $this->route()->getActionMethod();

        switch ($action) {
            case 'store':
                return [
                    'name' => 'required|string|max:255',
                    'code' => 'required|string|max:255|unique:collaborators,code',
                    'fee_percent' => 'required|numeric',
                ];

            case 'update':
                return [
                    'id' => 'required|exists:collaborators,id',
                    'name' => 'required|string|max:255',
                    'code' => 'required|string|max:255|unique:collaborators,code,' . $this->input('id'),
                    'fee_percent' => 'required|numeric',
                ];

            case 'delete':
                return [
                    'id' => 'required|exists:collaborators,id',
                ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
