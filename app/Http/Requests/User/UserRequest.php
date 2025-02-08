<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            case 'register':
                return [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6',
                    'role_name' => 'required|exists:roles,name',
                ];

            case 'updateRole':
                return [
                    'id' => 'required|numeric|exists:users,id',
                    'role_name' => 'required|exists:roles,name',
                ];

            case 'delete':
                return [
                    'id' => 'required|numeric|exists:users,id|not_in:' . $this->user()->id
                ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được quá 255 ký tự',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            // 'password.confirmed' => 'Xác nhận mật khẩu không đúng',
            'id.required' => 'ID không được để trống',
            'id.numeric' => 'ID phải là số',
            'id.exists' => 'ID không tồn tại',
            'id.not_in' => 'Không được xóa tài khoản bạn đang sử dụng',
            'role_name.required' => 'Vui lòng chọn vai trò',
            'role_name.exists' => 'Vai trò không tồn tại',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
