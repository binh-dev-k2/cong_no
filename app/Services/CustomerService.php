<?php

namespace App\Services;

use App\Http\Requests\Customer\addCustomerRequest;
use App\Models\Card;
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

    function create($data)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone']
            ]);

            $result = $this->cardService->assignCustomer($data['card_ids'], $customer->id);

            if ($result) {
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
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

            $customer->update([
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone']
            ]);

            $result = $this->cardService->assignCustomer($data['card_ids'], $customer->id);

            if ($result) {
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
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
            $customers = Customer::whereIn('id', $customer_ids)->delete();
            if (!$customers) {
                return false;
            }
            $result = $this->cardService->unassignCustomer($customer_ids);
            if ($result){
                DB::commit();
                return true;
            }
            DB::rollBack();
            return false;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }

}
