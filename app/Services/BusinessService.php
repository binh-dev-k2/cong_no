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
use Illuminate\Support\Facades\DB;

class BusinessService extends BaseService
{
    /**
     * Filter businesses for datatable
     *
     * @param array $data
     * @return array
     */
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

        $query->orderBy('id', 'desc');
        $recordsFiltered = $recordsTotal = $query->count();

        $businesses = $query->skip($skip)
            ->with(['bank', 'money', 'card', 'machine', 'collaborator'])
            ->withCount([
                'money' => function ($query) {
                    $query->where('money', '!=', 0);
                }
            ])
            ->take($pageLength)
            ->get();

        $moneyRecordCount = $businesses->max('money_count');

        $businesses->map(function ($business) {
            $isPaid = $business->money->where('is_note_checked', false);
            $business->is_paid = count($isPaid) > 0 ? false : true;
            return $business;
        });

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $businesses,
            'money_record_count' => $moneyRecordCount
        ];
    }

    /**
     * Store a new business
     *
     * @param array $data
     * @return bool
     */
    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $data = $this->calculateBusinessFee($data);
            // logger($data);
            if (!$data['is_stranger']) {
                $card = Card::where('card_number', $data['card_number'])->first();
                $data['bank_code'] = $card->bank->code;
            }

            $business = Business::create($data);

            $this->processBusinessMoney($business, $data);
            $this->updateTotalInvestment($business->id);

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            $this->handleException($th);
            DB::rollBack();
            return false;
        }
    }

    /**
     * Calculate business fee based on formality
     *
     * @param array $data
     * @return array
     */
    private function calculateBusinessFee(array $data)
    {
        switch ($data['formality']) {
            case 'R':
                $fee = (float) ($data['total_money'] * $data['fee_percent'] / 100);
                $data['fee'] = (float) ($data['total_money'] - $fee);
                break;

            default:
                $data['fee'] = (float) ($data['total_money'] * $data['fee_percent'] / 100);
                break;
        }

        return $data;
    }

    /**
     * Process business money based on settings
     *
     * @param Business $business
     * @param array $data
     * @return void
     */
    private function processBusinessMoney(Business $business, array $data)
    {
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
                $this->processPercentBasedMoney($business->id, $data['total_money'], $businessMoneySetting);
                break;
        }
    }

    /**
     * Process percent-based money calculation
     *
     * @param int $businessId
     * @param float $totalMoney
     * @param array $businessMoneySetting
     * @return void
     */
    private function processPercentBasedMoney(int $businessId, float $totalMoney, array $businessMoneySetting)
    {
        BusinessMoney::where('business_id', $businessId)->delete();
        $moneyData = [];

        foreach ($businessMoneySetting as $bsm) {
            $fee = $totalMoney * $bsm / 100;
            $moneyData[] = $this->createMoneyData($businessId, $fee);
        }

        BusinessMoney::insert($moneyData);
    }

    /**
     * Update a business
     *
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        $business = Business::findOrFail($data['id']);
        $data = $this->calculateBusinessFee($data);

        return $business->update($data);
    }

    /**
     * Update total investment
     *
     * @param int $businessId
     * @param float|null $plusMoney
     * @return bool|false
     */
    public function updateTotalInvestment($businessId, $plusMoney = null)
    {
        $totalInvestment = Setting::where('key', 'total_investment')->first();
        if (!$totalInvestment || now()->lt('2025-06-01')) {
            return false;
        }

        if (is_numeric($plusMoney)) {
            $totalInvestment->value += (float) $plusMoney;
        } else {
            $business = Business::findOrFail($businessId);
            if ($business->created_at->gte('2025-06-01')) {
                $totalInvestment->value -= (float) $business->total_money;
            }
        }

        return $totalInvestment->save();
    }

    /**
     * Get machine fee percent based on card number
     *
     * @param Business $business
     * @return float
     */
    public function getMachineFeePercent($business)
    {
        $firstNumber = (string) substr($business->card_number, 0, 1);
        $firstTwoNumbers = (string) substr($business->card_number, 0, 2);
        $machine = $business->machine;

        switch ($firstNumber) {
            case '3':
                // JCB cards have 16 digits, AMEX cards have 15 digits
                if (strlen((string) $business->card_number) === 16) {
                    return $machine->jcb_fee_percent;
                } else {
                    return $machine->amex_fee_percent;
                }

            case '4': // VISA
                return $machine->visa_fee_percent;

            case '5': // MasterCard
                // MasterCard numbers start with 50-55
                if ($firstTwoNumbers >= '50' && $firstTwoNumbers <= '55') {
                    return $machine->master_fee_percent;
                }
                return 0;

            case '6':
            case '7':
                return $machine->visa_fee_percent;

            case '9': // NAPAS
                return $machine->napas_fee_percent;
        }
        return 0;
    }

    /**
     * Complete a business transaction
     *
     * @param int $id
     * @return bool
     */
    public function complete($id)
    {
        DB::beginTransaction();
        try {
            $business = Business::where('id', $id)->with(['card', 'machine'])->first();

            $this->createDebtFromBusiness($business);
            $this->processBusinessFees($business);

            // Calculate money back after deducting machine fee
            $moneyBack = (float) ($business->total_money - ($business->total_money * $this->getMachineFeePercent($business) / 100));
            $this->updateTotalInvestment($business->id, $moneyBack);

            $business->delete();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            $this->handleException($th);
            DB::rollBack();
            return false;
        }
    }

    /**
     * Create debt record from business
     *
     * @param Business $business
     * @return void
     */
    private function createDebtFromBusiness(Business $business)
    {
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
            $debtData['fee'] = (float) ($business->total_money * ($business->fee_percent ?? $business->card->fee_percent) / 100);
            $debtData['total_amount'] = $debtData['fee'] + ($business->pay_extra ?? 0);
        }

        Debt::create($debtData);
    }

    /**
     * Process business fees for machine and collaborator
     *
     * @param Business $business
     * @return void
     */
    private function processBusinessFees(Business $business)
    {
        if ($business->machine) {
            $this->createMachineFee($business);
        }

        if ($business->collaborator) {
            $this->createCollaboratorFee($business);
        }
    }

    /**
     * Create machine fee record
     *
     * @param Business $business
     * @return void
     */
    private function createMachineFee(Business $business)
    {
        MachineBusinessFee::create([
            'machine_id' => $business->machine_id,
            'total_money' => $business->total_money,
            'fee' => (float) ($business->total_money * ($business->fee_percent - $this->getMachineFeePercent($business)) / 100),
            'month' => now()->month,
            'year' => now()->year
        ]);
    }

    /**
     * Create collaborator fee record
     *
     * @param Business $business
     * @return void
     */
    private function createCollaboratorFee(Business $business)
    {
        CollaboratorBusinessFee::create([
            'collaborator_id' => $business->collaborator_id,
            'total_money' => $business->total_money,
            'fee' => (float) ($business->total_money * ($business->fee_percent - $business->collaborator->fee_percent) / 100),
            'month' => now()->month,
            'year' => now()->year
        ]);
    }

    /**
     * Update business pay extra
     *
     * @param array $data
     * @return bool
     */
    public function updatePayExtra($data)
    {
        return Business::findOrFail($data['id'])->update(['pay_extra' => $data['pay_extra']]);
    }

    /**
     * Update or create business money
     *
     * @param array $data
     * @return bool
     */
    public function updateBusinessMoney($data)
    {
        if ($data['id']) {
            return BusinessMoney::findOrFail($data['id'])->update([
                'money' => $data['money'] ?? 0,
                'is_money_checked' => $data['is_money_checked'],
                'note' => $data['note'] ?? '',
                'is_note_checked' => $data['is_note_checked'],
            ]);
        }

        return BusinessMoney::create([
            'business_id' => $data['business_id'],
            'money' => $data['money'] ?? 0,
            'is_money_checked' => $data['is_money_checked'],
            'note' => $data['note'] ?? '',
            'is_note_checked' => $data['is_note_checked'],
        ]);
    }

    /**
     * Generate random money within range
     *
     * @param int $min
     * @param int $max
     * @return int
     */
    public function randomMoney($min, $max)
    {
        return random_int($min, $max);
    }

    /**
     * Calculate fee distribution
     *
     * @param int $businessId
     * @param float $totalMoney
     * @param int $minRangeMoney
     * @param int $maxRangeMoney
     * @return bool
     */
    public function calculateFee($businessId, $totalMoney, $minRangeMoney, $maxRangeMoney)
    {
        BusinessMoney::where('business_id', $businessId)->delete();
        $data = [];

        while ($totalMoney > 0) {
            $randomMoney = $this->randomMoney($minRangeMoney, $maxRangeMoney);
            if ($totalMoney >= $randomMoney) {
                $data[] = $this->createMoneyData($businessId, $randomMoney);
                $totalMoney -= $randomMoney;
            } else {
                $totalMoney = $this->distributeRemainingMoney($data, $businessId, $totalMoney, $maxRangeMoney);
            }
        }

        return BusinessMoney::insert($data);
    }

    /**
     * Create money data array
     *
     * @param int $businessId
     * @param int $money
     * @return array
     */
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

    /**
     * Distribute remaining money among existing entries
     *
     * @param array $data
     * @param int $businessId
     * @param int $remainingMoney
     * @param int $max
     * @return int
     */
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

    /**
     * Delete a business
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $business = Business::findOrFail($id);
        $this->updateTotalInvestment($business->id, $business->total_money);
        return $business->delete();
    }

    /**
     * Update business note
     *
     * @param array $data
     * @return mixed
     */
    public function updateNote($data)
    {
        return Setting::updateOrCreate(['key' => 'business_note'], ['value' => $data['business_note']]);
    }
}
