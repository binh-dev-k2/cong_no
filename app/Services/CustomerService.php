<?php

namespace App\Services;

use App\Http\Requests\Customer\addCustomerRequest;
use App\Models\Customer;
class CustomerService
{

    function ShowAll()
    {
        // Lấy dữ liệu từ cơ sở dữ liệu như bình thường
        $customers = Customer::join('card', 'customer.id', '=', 'card.customer_id')
            ->select('customer.name', 'customer.phone', 'card.bank_id','card.card_number', 'card.date_due', 'card.date_return')
            ->orderBy('customer.phone', 'desc')
            ->get();

        // Khởi tạo mảng kết quả
        $groupedData = [];

        // Lặp qua từng khách hàng
        foreach ($customers as $customer) {
            $phone = $customer->phone;

            // Kiểm tra xem khách hàng đã được thêm vào mảng kết quả chưa
            if (!isset($groupedData[$phone])) {
                // Nếu chưa thêm, thì thêm vào mảng kết quả
                $groupedData[$phone] = [
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'bank_id' => $customer->bank_id,
                    'card_number' => $customer->card_number,
                    'date_due' => $customer->date_due,
                    'date_return' => $customer->date_return,
                ];
            } else {
                // Nếu đã thêm, kiểm tra các trường dữ liệu card_number, date_due, date_return và cập nhật nếu cần
                if ($customer->card_number !== $groupedData[$phone]['card_number']) {
                    // Cập nhật card_number nếu khác
                    $groupedData[$phone]['card_number'] .= ', ' . $customer->card_number;
                }

                if ($customer->bank_id !== $groupedData[$phone]['bank_id']) {
                    // Cập nhật bank_id nếu khác
                    $groupedData[$phone]['bank_id'] .= ', ' . $customer->bank_id;
                }

                if ($customer->date_due !== $groupedData[$phone]['date_due']) {
                    // Cập nhật date_due nếu khác
                    $groupedData[$phone]['date_due'] .= ', ' . $customer->date_due;
                }

                if ($customer->date_return !== $groupedData[$phone]['date_return']) {
                    // Cập nhật date_return nếu khác
                    $groupedData[$phone]['date_return'] .= ', ' . $customer->date_return;
                }
            }
        }

        // Chuyển đổi mảng kết quả thành collection và trả về
        return collect(array_values($groupedData));
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
