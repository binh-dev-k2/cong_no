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

            $this->cardService->assignCustomer($data['card_ids'], $customer->id);

            DB::commit();
            return $customer;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return null;
        }
    }

    public function update($data)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::find($data['id']);
            if (!$customer) {
                return null;
            }

            $customer->update([
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone'],
            ]);

            $this->cardService->assignCustomer($data['card_ids'], $customer->id);

            DB::commit();
            return $customer;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return null;
        }
    }

    public function delete(array $customer_ids)
    {
        DB::beginTransaction();
        try {
            Customer::whereIn('id', $customer_ids)->delete();

            $this->cardService->unassignCustomer($customer_ids);

            CardHistory::whereIn('customer_id', $customer_ids)->delete();

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }
}
