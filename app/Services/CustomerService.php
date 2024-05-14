<?php

namespace App\Services;

use App\Http\Requests\Customer\addCustomerRequest;
use App\Models\Card;
use App\Models\CardHistory;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    protected $cardService;

    public function __construct()
    {
        $this->cardService = $this->cardServiceProperty();
    }

    public function cardServiceProperty()
    {
        return app(CardService::class);
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone']
            ]);

            if (!$customer) {
                Log::error("Failed to create customer with data: " . json_encode($data));
                DB::rollBack();
                return false;
            }

            // Gán thẻ cho khách hàng
            $result = $this->cardService->assignCustomer($data['card_ids'], $customer->id);

            if (!$result) {
                Log::error("Failed to assign cards to customer with ID: " . $customer->id);
                DB::rollBack();
                return false;
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }

    public function update($data)
    {
        try {
            DB::beginTransaction();
            $customer = Customer::where('id', $data['id'])->first();
            if (!$customer) {
                return false;
            }

            // Cập nhật thông tin khách hàng
            $customerUpdated = $customer->update([
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone']
            ]);

            if (!$customerUpdated) {
                Log::error("Failed to update customer with ID: " . $data['id']);
                DB::rollBack();
                return false;
            }

            // Gán khách hàng với các thẻ
            $result = $this->cardService->assignCustomer($data['card_ids'], $customer->id);

            if (!$result) {
                Log::error("Failed to assign cards to customer with ID: " . $data['id']);
                DB::rollBack();
                return false;
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }

    public function delete(array $customer_ids)
    {
        try {
            DB::beginTransaction();

            // Xóa khách hàng
            $customersDeleted = Customer::whereIn('id', $customer_ids)->delete();
            if (!$customersDeleted) {
                DB::rollBack();
                Log::error("Failed to delete customers with IDs: " . implode(', ', $customer_ids));
                return false;
            }

            // Hủy gán khách hàng theo card
            $unassignResult = $this->cardService->unassignCustomer($customer_ids);
            if (!$unassignResult) {
                DB::rollBack();
                Log::error("Failed to unassign customers from cardService with IDs: " . implode(', ', $customer_ids));
                return false;
            }

            // Xóa lịch sử thẻ của khách hàng
            $cardHistoryDeleted = CardHistory::whereIn('customer_id', $customer_ids)->delete();
            if (!$cardHistoryDeleted) {
                DB::rollBack();
                Log::error("Failed to delete card history for customers with IDs: " . implode(', ', $customer_ids));
                return false;
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }
}
