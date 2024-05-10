<?php

namespace App\Services;

use App\Http\Requests\Customer\addCustomerRequest;
use App\Models\Card;
use App\Models\Customer;

class CustomerService
{

    function datatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 10;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Card::query();

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->whereHas('customer', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })->orWhere('account_number', 'like', "%{$search}%");
        }

        switch ($data['order'][0]['column']) {
            case '0':
                $orderBy = 'id';
                break;

            default:
                $orderBy = 'id';
                break;
        }

        $query->orderBy('customer_id', 'desc')->orderBy($orderBy, $data['order'][0]['dir'] ?? 'desc');

        $recordsFiltered = $recordsTotal = $query->count();
        $customers = $query->skip($skip)
            ->with(['customer', 'bank'])
            ->take($pageLength)
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $customers
        ];
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
