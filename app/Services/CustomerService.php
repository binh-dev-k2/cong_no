<?php

namespace App\Services;

use App\Http\Requests\Customer\addCustomerRequest;
use App\Models\Customer;
class CustomerService
{

    function ShowAll() {
        return Customer::join('card', 'customer.id', '=', 'card.customer_id')
            ->select('customer.*', 'card.card_number')
            ->orderBy('customer.id','desc')->get();
    }
    function save(addCustomerRequest $request){
        $result = Customer::create([
            'name' => $request->customer_name,
            'phone' => $request->customer_phone
        ]);
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
