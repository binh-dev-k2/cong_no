<?php

namespace App\Services;

use App\Models\Business;
use App\Models\BusinessMoney;
use App\Models\BusinessSetting;
use App\Models\Card;
use App\Models\Debt;
use App\Models\MachineBusinessFee;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BusinessService extends BaseService
{
    public function filterDatatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
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
            ->with(['bank', 'money', 'card', 'machine'])
            ->withCount([
                'money' => function ($query) {
                    $query->where('money', '!=', 0);
                }
            ])
            ->take($pageLength)
            ->get();

        $moneyRecordCount = $businnesses->max('money_count');

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $businnesses,
            'money_record_count' => $moneyRecordCount
        ];
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            switch ($data['formality']) {
                case 'R':
                    $fee = (float) ($data['total_money'] * $data['fee_percent'] / 100);
                    $data['fee'] = (float) ($data['total_money'] - $fee);
                    break;

                default:
                    $data['fee'] = (float) ($data['total_money'] * $data['fee_percent'] / 100);
                    break;
            }

            $card = Card::where('card_number', $data['card_number'])->first();
            $data['bank_code'] = $card->bank->code;
            $business = Business::create($data);

            $businessMoneySetting = BusinessSetting::where('type', $data['business_setting_type'])
                ->where('key', $data['business_setting_key'])
                ->pluck('value')
                ->toArray();

            switch ($data['business_setting_type']) {
                case 'MONEY':
                    $minRangeMoney = (int) min($businessMoneySetting);
                    $maxRangeMoney = (int) max($businessMoneySetting);

                    $this->calculateFee($business->id, $data['total_money'], $minRangeMoney, $maxRangeMoney);
                    break;

                case 'PERCENT':
                    BusinessMoney::where('business_id', $business->id)->delete();
                    $i = 0;
                    $moneyData = [];

                    foreach ($businessMoneySetting as $bsm) {
                        $fee = $data['total_money'] * $bsm / 100;
                        $moneyData[] = $this->createMoneyData($business->id, $fee);
                        $i++;
                    }

                    for ($i; $i < 10; $i++) {
                        $moneyData[] = $this->createMoneyData($business->id, 0);
                    }

                    BusinessMoney::insert($moneyData);
                    break;
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            $this->handleException($th);
            DB::rollBack();
            return false;
        }
    }

    public function update(array $data)
    {
        $business = Business::findOrFail($data['id']);
        return $business->update($data);
    }

    public function complete($id)
    {
        try {
            DB::beginTransaction();
            $business = Business::where('id', $id)->with(['card', 'machine'])->first();

            $debtData = [
                'account_name' => $business->account_name,
                'name' => $business->name,
                'phone' => $business->phone,
                'card_number' => $business->card_number,
                'total_money' => $business->total_money,
                'formality' => $business->formality,
                'fee' => $business->fee ?? 0,
                'pay_extra' => $business->pay_extra ?? 0,
                'status' => Debt::STATUS_UNPAID,
                'total_amount' => ($business->fee ?? 0) + ($business->pay_extra ?? 0),
                'business_id' => $business->id
            ];

            if ($business->formality == 'R') {
                $debtData['fee'] = (float) ($business->total_money * ($business->card->fee_percent ?? $business->fee_percent) / 100);
                $debtData['total_amount'] = $debtData['fee'] + ($business->pay_extra ?? 0);
            }

            Debt::create($debtData);

            if ($business->machine) {
                MachineBusinessFee::create([
                    'machine_id' => $business->machine_id,
                    'fee' => $business->fee - (float) ($business->total_money * $business->machine->fee_percent / 100),
                    'month' => now()->month,
                    'year' => now()->year
                ]);
            }

            $business->delete();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            $this->handleException($th);
            DB::rollBack();
            return false;
        }
    }

    public function updatePayExtra($data)
    {
        return Business::findOrFail($data['id'])->update(['pay_extra' => $data['pay_extra']]);
    }

    public function updateBusinessMoney($data)
    {
        return BusinessMoney::findOrFail($data['id'])->update(
            [
                'money' => $data['money'] ?? 0,
                'is_money_checked' => $data['is_money_checked'],
                'note' => $data['note'] ?? '',
                'is_note_checked' => $data['is_note_checked'],
            ]
        );
    }

    public function randomMoney($min, $max)
    {
        return random_int($min, $max);
    }

    public function calculateFee($businessId, $totalMoney, $minRangeMoney, $maxRangeMoney)
    {
        BusinessMoney::where('business_id', $businessId)->delete();
        $data = [];
        $i = 0;
        while ($totalMoney > 0) {
            $i++;
            logger($totalMoney);
            $randomMoney = $this->randomMoney($minRangeMoney, $maxRangeMoney);
            if ($totalMoney >= $randomMoney) {
                $data[] = $this->createMoneyData($businessId, $randomMoney);
                $totalMoney -= $randomMoney;
            } else {
                $totalMoney = $this->distributeRemainingMoney($data, $businessId, $totalMoney, $maxRangeMoney);
            }
        }

        for ($i; $i < 10; $i++) {
            $data[] = $this->createMoneyData($businessId, 0);
        }

        return BusinessMoney::insert($data);
    }

    private function createMoneyData(int $businessId, int $money): array
    {
        return [
            'business_id' => $businessId,
            'money' => $money,
            'is_money_checked' => false,
            'is_note_checked' => false,
            'created_at' => now('Asia/Ho_Chi_Minh'),
        ];
    }

    private function distributeRemainingMoney(array &$data, int $businessId, int $remainingMoney, $max): int
    {
        foreach ($data as &$entry) {
            if ($entry['money'] + $remainingMoney <= $max) {
                $entry['money'] += $remainingMoney;
                return 0;
            }
        }

        $data[] = $this->createMoneyData($businessId, $remainingMoney);
        return 0;
    }

    public function delete($id)
    {
        return Business::findOrFail($id)->delete() > 0;
    }

    public function updateNote($data)
    {
        return Setting::updateOrCreate(['key' => 'business_note'], ['value' => $data['business_note']]);
    }

    public function updateSetting($data)
    {
        BusinessSetting::truncate();
        DB::beginTransaction();
        try {
            foreach ($data as $value) {
                BusinessSetting::create([
                    'type' => $value['type'],
                    'key' => $value['key'],
                    'value' => $value['value']
                ]);
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return false;
        }
    }
}
