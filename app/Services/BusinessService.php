<?php

namespace App\Services;

use App\Models\Business;
use App\Models\BusinessMoney;
use App\Models\Debt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BusinessService
{
    public function filterDatatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 10;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Business::query();

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('card_number', 'like', "%{$search}%");
        }

        // switch ($data['order'][0]['column']) {
        //     case '0':
        //         $orderBy = 'id';
        //         break;

        //     default:
        //         $orderBy = 'id';
        //         break;
        // }

        $query->orderBy('id', 'desc');

        $recordsFiltered = $recordsTotal = $query->count();
        $businnesses = $query->skip($skip)
            ->with(['bank'])
            ->take($pageLength)
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $businnesses
        ];
    }

    public function store(array $data)
    {
        try {
            $data['fee'] = (float)($data['total_money'] * $data['fee_percent'] / 100);
            $business = Business::create($data);

            if (!$business) {
                DB::rollBack();
                return false;
            }

            $resultCalculaterFee = $this->calculateFee($business->id, $data['total_money']);
            if (!$resultCalculaterFee) {
                DB::rollBack();
                return false;
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }

    public function complete($id)
    {
        try {
            DB::beginTransaction();
            $business = Business::where('id', $id)->first();
            if (!$business) {
                return false;
            }

            $debt = Debt::create([
                'name' => $business->name,
                'phone' => $business->phone,
                'card_number' => $business->card_number,
                'formality' => $business->formality,
                'fee' => $business->fee,
                'pay_extra' => $business->pay_extra ?? 0,
                'status' => Debt::STATUS_UNPAID,
                'total_amount' => $business->fee + $business->pay_extra ?? 0,
            ]);

            if (!$debt) {
                DB::rollBack();
                return false;
            }

            $business->delete();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }

    public function updatePayExtra($data)
    {
        return Business::where('id', $data['id'])->update(['pay_extra' => $data['pay_extra']]);
    }

    public function getBusinessMoney(int $id)
    {
        return BusinessMoney::where('business_id', $id)->get();
    }

    public function updateNoteBusinessMoney($data)
    {
        return BusinessMoney::where('id', $data['id'])->update(['note' => $data['note']]);
    }

    public function randomMoney()
    {
        return random_int(34000000, 35000000);
    }

    public function calculateFee($businessId, $totalMoney)
    {
        BusinessMoney::where('business_id', $businessId)->delete();
        $data = [];

        while ($totalMoney > 0) {
            $randomMoney = $this->randomMoney();
            if ($totalMoney >= $randomMoney) {
                $data[] = $this->createMoneyData($businessId, $randomMoney);
                $totalMoney -= $randomMoney;
            } else {
                $totalMoney = $this->distributeRemainingMoney($data, $businessId, $totalMoney);
            }
        }

        return BusinessMoney::insert($data);
    }

    private function createMoneyData(int $businessId, int $money): array
    {
        return [
            'business_id' => $businessId,
            'money' => $money
        ];
    }

    private function distributeRemainingMoney(array &$data, int $businessId, int $remainingMoney): int
    {
        foreach ($data as &$entry) {
            if ($entry['money'] + $remainingMoney <= 35000000) {
                $entry['money'] += $remainingMoney;
                return 0;
            }
        }

        $data[] = $this->createMoneyData($businessId, $remainingMoney);
        return 0;
    }
}
