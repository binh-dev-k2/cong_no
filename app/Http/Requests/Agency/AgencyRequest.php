<?php

namespace App\Http\Requests\Agency;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AgencyRequest extends FormRequest
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
                    'name' => 'required|string|max:255|unique:agencies,name',
                    'fee_percent' => 'required|numeric|min:0|max:100',
                    'machines' => 'nullable|array',
                    'machines.*' => 'exists:machines,id'
                ];

            case 'update':
                return [
                    'id' => 'required|exists:agencies,id',
                    'name' => 'required|string|max:255|unique:agencies,name,' . $this->input('id'),
                    'fee_percent' => 'required|numeric|min:0|max:100',
                    'machines' => 'nullable|array',
                    'machines.*' => 'exists:machines,id'
                ];

            case 'destroy':
                return [
                    'id' => 'required|exists:agencies,id'
                ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên đại lý là gì? Bạn vui lòng nhập tên đại lý nhé!',
            'name.string' => 'Tên đại lý phải là chữ nhé!',
            'name.max' => 'Tên đại lý không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên đại lý này đã tồn tại trong hệ thống của chúng tôi rồi.',
            'fee_percent.required' => 'Bạn vui lòng nhập phần trăm phí cho đại lý.',
            'fee_percent.numeric' => 'Phần trăm phí phải là số nhé!',
            'fee_percent.min' => 'Phần trăm phí không được nhỏ hơn 0.',
            'fee_percent.max' => 'Phần trăm phí không được lớn hơn 100.',
            'id.required' => 'ID đại lý là bắt buộc, bạn vui lòng nhập nhé!',
            'id.exists' => 'Đại lý này không tồn tại trong hệ thống của chúng tôi.',
            'machines.array' => 'Danh sách máy phải là một mảng.',
            'machines.*.exists' => 'Một số máy được chọn không tồn tại trong hệ thống của chúng tôi.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
