<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
                    'name' => 'required|string|unique:roles,name',
                    'permissions' => 'required|array',
                    'permissions.*' => 'exists:permissions,name',
                ];

            case 'update':
                return [
                    'id' => 'required|exists:roles,id',
                    'name' => 'required|string|unique:roles,name,' . $this->route('role')->id,
                    'permissions' => 'required|array',
                    'permissions.*' => 'exists:permissions,name',
                ];

            case 'delete':
                return [
                    'id' => 'required|exists:roles,id',
                ];
        }
    }
}
