<?php

namespace App\Http\Requests\Machine;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MachineRequest extends FormRequest
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
                    'code' => 'required|string|max:255|unique:machines,code',
                    'visa_fee_percent' => 'required|numeric|min:0',
                    'master_fee_percent' => 'required|numeric|min:0',
                    'jcb_fee_percent' => 'required|numeric|min:0',
                    'napas_fee_percent' => 'required|numeric|min:0',
                    'amex_fee_percent' => 'required|numeric|min:0',
                    'status' => 'required|in:0,1'
                ];

            case 'update':
                return [
                    'id' => 'required|exists:machines,id',
                    'name' => 'required|string|max:255',
                    'code' => 'required|string|max:255|unique:machines,code,' . $this->input('id'),
                    'visa_fee_percent' => 'required|numeric|min:0',
                    'master_fee_percent' => 'required|numeric|min:0',
                    'jcb_fee_percent' => 'required|numeric|min:0',
                    'napas_fee_percent' => 'required|numeric|min:0',
                    'amex_fee_percent' => 'required|numeric|min:0',
                    'status' => 'required|in:0,1'
                ];
        }
    }

    public function messages()
    {
        return [
            'visa_fee_percent.numeric' => 'Phần trăm phí VISA phải là số',
            'master_fee_percent.numeric' => 'Phần trăm phí MasterCard phải là số',
            'jcb_fee_percent.numeric' => 'Phần trăm phí JCB phải là số',
            'napas_fee_percent.numeric' => 'Phần trăm phí NAPAS phải là số',
            'amex_fee_percent.numeric' => 'Phần trăm phí AMEX phải là số',
            'amex_fee_percent.required' => 'Phần trăm phí AMEX là bắt buộc',
            'napas_fee_percent.required' => 'Phần trăm phí NAPAS là bắt buộc',
            'master_fee_percent.required' => 'Phần trăm phí MasterCard là bắt buộc',
            'jcb_fee_percent.required' => 'Phần trăm phí JCB là bắt buộc',
            'visa_fee_percent.required' => 'Phần trăm phí VISA là bắt buộc',
            'fee_percent.numeric' => 'Phần trăm phí phải là số',
            'fee_percent.required' => 'Phần trăm phí là bắt buộc',
            'id.required' => 'Máy không tồn tại',
            'id.exists' => 'Máy không tồn tại',
            'name.required' => 'Tên máy là bắt buộc',
            'name.string' => 'Tên máy phải là chuỗi',
            'name.max' => 'Tên máy không được vượt quá 255 ký tự',
            'code.required' => 'Mã máy là bắt buộc',
            'code.string' => 'Mã máy phải là chuỗi',
            'code.max' => 'Mã máy không được vượt quá 255 ký tự',
            'code.unique' => 'Mã máy đã tồn tại',
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(jsonResponse(1, $errors));
    }
}
