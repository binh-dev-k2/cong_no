<?php

namespace App\Services;

use App\Http\Requests\Customer\addCustomerRequest;
use App\Models\Customer;

class CustomerService
{

    function ShowAll()
    {
        $customers = Customer::join('card', 'customer.id', '=', 'card.customer_id')
//            ->join('card_remind_history', 'card.id', '=', 'card_remind_history.card_id')
            ->select('customer.name', 'customer.phone', 'card.bank_id', 'card.card_number', 'card.date_due', 'card.date_return')
            ->orderBy('customer.phone', 'desc')
            ->get();
        $groupedData = [];

        foreach ($customers as $customer) {
            $phone = $customer->phone;

            if (!isset($groupedData[$phone])) {
                $groupedData[$phone] = [
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'bank_id' => $customer->bank_id,
                    'card_number' => $customer->card_number,
                    'date_due' => $customer->date_due,
                    'date_return' => $customer->date_return,
                    'remind_date' => $customer->datetime ?: null,
                ];
            } else {
                if ($customer->card_number !== $groupedData[$phone]['card_number']) {
                    $groupedData[$phone]['card_number'] .= ', ' . $customer->card_number;
                }
                if ($customer->bank_id !== $groupedData[$phone]['bank_id']) {
                    $groupedData[$phone]['bank_id'] .= ', ' . $customer->bank_id;
                }
                if ($customer->date_due !== $groupedData[$phone]['date_due']) {
                    $groupedData[$phone]['date_due'] .= ', ' . $customer->date_due;
                }

                if ($customer->date_return !== $groupedData[$phone]['date_return']) {
                    $groupedData[$phone]['date_return'] .= ', ' . $customer->date_return;
                }
            }
        }

        return collect(array_values($groupedData));
    }


    function save(addCustomerRequest $request)
    {
        $result = Customer::create([
            'name' => $request->customer_name,
            'phone' => $request->customer_phone
        ]);
        if ($result) {
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

    function delete($phone)
    {
        $result = Customer::where('phone', $phone)->delete();
        if ($result) {
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
