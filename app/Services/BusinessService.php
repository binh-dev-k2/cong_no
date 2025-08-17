<?php

namespace App\Services;

use App\Models\Business;
use App\Models\BusinessMoney;
use App\Models\BusinessSetting;
use App\Models\Card;
use App\Models\Debt;
use App\Models\MachineBusinessFee;
use App\Models\CollaboratorBusinessFee;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BusinessService extends BaseService
{
    public function filterDatatable(array $data)
    {
        $start = $data['start'] ?? 0;
        $length = $data['length'] ?? 50;
        $pageNumber = ($start / $length) + 1;
        $skip = ($pageNumber - 1) * $length;

        $query = Business::query();

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('card_number', 'like', "%{$search}%");
            });
        }

        $query->orderBy('id', 'desc');

        $recordsFiltered = $recordsTotal = $query->count();

        $businesses = $query->skip($skip)
            ->with(['bank', 'money', 'card', 'machine', 'collaborator'])
            ->withCount([
                'money' => function ($query) {
                    $query->where('money', '!=', 0);
                }
            ])
            ->take($length)
            ->get();

        $businesses->transform(function ($business) {
            $isPaid = $business->money->where('is_note_checked', false);
            $business->is_paid = $isPaid->isEmpty();
            return $business;
        });

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $businesses,
            'money_record_count' => $businesses->max('money_count')
        ];
    }

    public function store(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $data = $this->calculateBusinessFee($data);

                if (empty($data['is_stranger'])) {
                    $data['bank_code'] = $this->getCardBankCode($data['card_number']);
                }

                $business = Business::create($data);

                $this->processBusinessMoney($business, $data);
                $this->updateTotalInvestment($business->id);

                return true;
            });
        } catch (\Throwable $th) {
            $this->handleException($th);
            return false;
        }
    }

    private function getCardBankCode(string $cardNumber): ?string
    {
        if (empty($cardNumber)) {
            return null;
        }

        $card = Card::where('card_number', $cardNumber)->with('bank')->first();
        return $card && $card->bank ? $card->bank->code : null;
    }

    private function calculateBusinessFee(array $data)
    {
        $totalMoney = (float) ($data['total_money'] ?? 0);
        $feePercent = (float) ($data['fee_percent'] ?? 0);
        $formality = $data['formality'] ?? null;

        if ($formality === 'R') {
            $fee = $totalMoney * $feePercent / 100.0;
            $data['fee'] = (float) ($totalMoney - $fee);
        } else {
            $data['fee'] = (float) ($totalMoney * $feePercent / 100.0);
        }

        return $data;
    }

    private function processBusinessMoney(Business $business, array $data)
    {
        $type = $data['business_setting_type'] ?? null;
        $key = $data['business_setting_key'] ?? null;

        if (empty($type) || empty($key)) {
            return;
        }

        $businessMoneySetting = BusinessSetting::where('type', $type)
            ->where('key', $key)
            ->pluck('value')
            ->toArray();

        if (empty($businessMoneySetting)) {
            return;
        }

        switch ($type) {
            case 'MONEY':
                $minRange = (int) min($businessMoneySetting);
                $maxRange = (int) max($businessMoneySetting);

                $this->calculateFee($business->id, $data['total_money'], $minRange, $maxRange);
                break;

            case 'PERCENT':
                $this->processPercentBasedMoney($business->id, $data['total_money'], $businessMoneySetting);
                break;

            default:
                break;
        }
    }

    private function processPercentBasedMoney(int $businessId, float $totalMoney, array $businessMoneySetting)
    {
        BusinessMoney::where('business_id', $businessId)->delete();

        $moneyData = array_map(function ($percent) use ($businessId, $totalMoney) {
            $fee = $totalMoney * ((float) $percent) / 100.0;
            return $this->createMoneyData($businessId, (int) round($fee));
        }, $businessMoneySetting);

        if (!empty($moneyData)) {
            BusinessMoney::insert($moneyData);
        }
    }

    public function update(array $data)
    {
        return DB::transaction(function () use ($data) {
            $business = Business::findOrFail($data['id']);
            $data = $this->calculateBusinessFee($data);

            return $business->update($data);
        });
    }

    public function updateTotalInvestment(int $businessId, ?float $plusMoney = null)
    {
        $totalInvestment = Setting::where('key', 'total_investment')->first();
        if (!$totalInvestment) {
            return false;
        }

        if (is_numeric($plusMoney)) {
            $totalInvestment->value = (float) $totalInvestment->value + (float) $plusMoney;
        } else {
            $business = Business::findOrFail($businessId);
            $totalInvestment->value -= (float) $business->total_money;
        }

        return (bool) $totalInvestment->save();
    }

    public function getMachineFeePercent(Business $business)
    {
        $cardNumber = (string) $business->card_number;
        $firstNumber = $cardNumber[0] ?? '';
        $firstTwo = (int) substr($cardNumber, 0, 2);
        $machine = $business->machine;

        if (!$machine) {
            return 0.0;
        }

        switch ($firstNumber) {
            case '3':
                // JCB cards have 16 digits, AMEX cards have 15 digits
                $len = strlen($cardNumber);
                return $len === 16 ? (float) $machine->jcb_fee_percent : (float) $machine->amex_fee_percent;

            case '4': // VISA
                return (float) $machine->visa_fee_percent;

            case '5': // MasterCard range 50-55
                return ($firstTwo >= 50 && $firstTwo <= 55) ? (float) $machine->master_fee_percent : 0.0;

            case '6':
            case '7':
                return (float) $machine->visa_fee_percent;

            case '9': // NAPAS
                return (float) $machine->napas_fee_percent;

            default:
                return 0.0;
        }
    }

    public function complete(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $business = Business::with(['card', 'machine', 'collaborator'])->findOrFail($id);

                $this->createDebtFromBusiness($business);
                $this->processBusinessFees($business);

                // Calculate money back after deducting machine fee
                $machinePercent = $this->getMachineFeePercent($business);
                $moneyBack = (float) ($business->total_money - ($business->total_money * $machinePercent / 100.0));

                $this->updateTotalInvestment($business->id, $moneyBack);

                $business->delete();

                return true;
            });
        } catch (\Throwable $th) {
            $this->handleException($th);
            return false;
        }
    }

    private function createDebtFromBusiness(Business $business)
    {
        $fee = (float) ($business->fee ?? 0);
        $payExtra = (float) ($business->pay_extra ?? 0);

        if ($business->formality === 'R') {
            $fee = (float) ($business->total_money * ($business->fee_percent ?? 0) / 100.0);
        }

        $debtData = [
            'account_name' => $business->account_name,
            'name' => $business->name,
            'phone' => $business->phone,
            'card_number' => $business->card_number,
            'total_money' => $business->total_money,
            'formality' => $business->formality,
            'fee' => $fee,
            'pay_extra' => $payExtra,
            'status' => Debt::STATUS_UNPAID,
            'total_amount' => $fee + $payExtra,
            'business_id' => $business->id,
            'machine_id' => $business->machine_id,
        ];

        Debt::create($debtData);
    }

    private function processBusinessFees(Business $business)
    {
        if ($business->machine_id) {
            $this->createMachineFee($business);
        }

        if ($business->collaborator_id) {
            $this->createCollaboratorFee($business);
        }
    }

    private function createMachineFee(Business $business)
    {
        $machinePercent = $this->getMachineFeePercent($business);
        $feePercent = (float) ($business->fee_percent ?? 0);

        MachineBusinessFee::create([
            'machine_id' => $business->machine_id,
            'total_money' => $business->total_money,
            'fee' => (float) ($business->total_money * ($feePercent - $machinePercent) / 100.0),
            'month' => now()->month,
            'year' => now()->year
        ]);
    }

    private function createCollaboratorFee(Business $business)
    {
        $collabPercent = (float) ($business->collaborator->fee_percent ?? 0);
        $feePercent = (float) ($business->fee_percent ?? 0);

        CollaboratorBusinessFee::create([
            'collaborator_id' => $business->collaborator_id,
            'total_money' => $business->total_money,
            'fee' => (float) ($business->total_money * ($feePercent - $collabPercent) / 100.0),
            'month' => now()->month,
            'year' => now()->year
        ]);
    }

    public function updatePayExtra($data)
    {
        $business = Business::findOrFail($data['id']);
        return (bool) $business->update(['pay_extra' => $data['pay_extra'] ?? 0]);
    }

    public function updateBusinessMoney(array $data): bool
    {
        $payload = [
            'money' => $data['money'] ?? 0,
            'is_money_checked' => $data['is_money_checked'] ?? false,
            'note' => $data['note'] ?? '',
            'is_note_checked' => $data['is_note_checked'] ?? false,
        ];

        if (!empty($data['id'])) {
            return (bool) BusinessMoney::findOrFail($data['id'])->update($payload);
        }

        $payload['business_id'] = $data['business_id'];
        return (bool) BusinessMoney::create($payload);
    }

    public function randomMoney(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    public function calculateFee(int $businessId, float $totalMoney, int $minRangeMoney, int $maxRangeMoney)
    {
        BusinessMoney::where('business_id', $businessId)->delete();
        $data = [];

        $remaining = (int) round($totalMoney);

        while ($remaining > 0) {
            $rand = $this->randomMoney($minRangeMoney, $maxRangeMoney);

            if ($remaining >= $rand) {
                $data[] = $this->createMoneyData($businessId, $rand);
                $remaining -= $rand;
            } else {
                // try to distribute the remainder into existing entries
                $remaining = $this->distributeRemainingMoney($data, $businessId, $remaining, $maxRangeMoney);
            }
        }

        return !empty($data) ? (bool) BusinessMoney::insert($data) : false;
    }

    private function createMoneyData(int $businessId, int $money): array
    {
        return [
            'business_id' => $businessId,
            'money' => $money,
            'is_money_checked' => false,
            'is_note_checked' => false,
            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
        ];
    }

    private function distributeRemainingMoney(array &$data, int $businessId, int $remainingMoney, int $max): int
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
        $business = Business::findOrFail($id);
        $this->updateTotalInvestment($business->id, (float) $business->total_money);
        return $business->delete();
    }

    public function updateNote($data)
    {
        return Setting::updateOrCreate(['key' => 'business_note'], ['value' => $data['business_note'] ?? '']);
    }
}
