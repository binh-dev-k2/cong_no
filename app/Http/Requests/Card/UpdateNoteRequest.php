<?php

namespace App\Http\Requests\Card;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateNoteRequest extends FormRequest
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
            'id' => 'required|exists:cards,id|numeric',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Bạn vui lòng nhập ID nhé!',
            'id.exists' => 'ID này không tồn tại trong hệ thống của chúng tôi. Bạn kiểm tra lại nhé!',
            'id.numeric' => 'ID phải là số, bạn kiểm tra lại nhé!',
            'note.string' => 'Ghi chú phải là chuỗi ký tự.',
            'note.max' => 'Ghi chú không được vượt quá 255 ký tự.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
