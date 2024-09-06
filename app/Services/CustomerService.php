<?php

namespace App\Services;

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
        DB::beginTransaction();
        try {
            $customer = Customer::create([
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone'],
                'fee_percent' => null,
            ]);

            if (!$customer) {
                return false;
            }

            // Gán thẻ cho khách hàng
            $result = $this->cardService->assignCustomer($data['card_ids'], $customer->id);

            if (!$result) {
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
        DB::beginTransaction();
        try {
            $customer = Customer::find($data['id']);
            if (!$customer) {
                return false;
            }

            // Cập nhật thông tin khách hàng
            $customerUpdated = $customer->update([
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone'],
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
        DB::beginTransaction();
        try {
            // Xóa khách hàng
            $customersDeleted = Customer::whereIn('id', $customer_ids)->delete();
            if (!$customersDeleted) {
                Log::error("Failed to delete customers with IDs: " . implode(', ', $customer_ids));
                return false;
            }

            // Hủy gán khách hàng theo card
            $unassignResult = $this->cardService->unassignCustomer($customer_ids);
            if (!$unassignResult) {
                Log::error("Failed to unassign customers from cardService with IDs: " . implode(', ', $customer_ids));
                return false;
            }

            // Xóa lịch sử thẻ của khách hàng
            $cardHistoryDeleted = CardHistory::whereIn('customer_id', $customer_ids)->delete();
            if (!$cardHistoryDeleted) {
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
