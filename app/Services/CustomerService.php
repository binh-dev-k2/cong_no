<?php

namespace App\Services;

use App\Http\Requests\Customer\addCustomerRequest;
use App\Models\Customer;
class CustomerService
{

    function ShowAll() {
        return Customer::all();
    }
    function save(addCustomerRequest $request){
        $result = Customer::create($request->all());
        if($result) {
            return [
                'success' => true,
                'data' => $result
            ];
        }
        return [
            'success' => false,
            'code' => 1
        ];
    }
}
