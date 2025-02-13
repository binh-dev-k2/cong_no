<?php

namespace App\Services;

use App\Models\BusinessMoney;
use App\Models\Debt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DebtService
{
    public function datatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Debt::query()
            ->with(['card.bank']);

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('card_number', 'like', "%{$search}%");
        }

        $query->whereYear('updated_at', $data['year'] ?? Carbon::now()->year);

        if (isset($data['month'])) {
            $query->where('status', Debt::STATUS_PAID)
                ->whereMonth('updated_at', $data['month']);
        } else {
            $query->where('status', Debt::STATUS_UNPAID);
        }

        $recordsFiltered = $recordsTotal = $query->count();
        $debts = $query
            ->orderBy('updated_at', 'desc')
            // ->orderBy('phone', 'asc')
            // ->skip($skip)
            // ->take($pageLength)
            ->get()
            ->toArray();

        $sortedDebts = $this->customSort($debts, $skip + $pageLength);

        $paginatedDebts = array_slice($sortedDebts, $skip, $pageLength);
        $this->calculateSumAmount($paginatedDebts, $data['month'], $data['year']);

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $paginatedDebts
        ];
    }

    public function customSort(array $array, int $length)
    {
        $sortedArray = [];

        while ($length > 0 && !empty($array)) {
            $item = $array[0];
            for ($i = 0; $i < count($array) && $length > 0; $i++) {
                if ($item['phone'] === $array[$i]['phone']) {
                    $sortedArray[] = $array[$i];
                    unset($array[$i]);
                    $array = array_values($array);
                    $i--;
                    $length--;
                }
            }
        }

        return $sortedArray;
    }

    public function calculateSumAmount(&$array, $month, $year)
    {
        $phoneArray = [];

        foreach ($array as &$item) {
            if (in_array($item['phone'], $phoneArray)) {
                $item['sum_amount'] = null;
                continue;
            }

            $phoneArray[] = $item['phone'];
            $sumAmount = $item['status'] === Debt::STATUS_PAID
                ? Debt::where('status', Debt::STATUS_PAID)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year ?? Carbon::now()->year)
                    ->where('phone', $item['phone'])
                    ->sum('total_amount')
                : Debt::where('status', Debt::STATUS_UNPAID)
                    ->whereYear('created_at', $year ?? Carbon::now()->year)
                    ->where('phone', $item['phone'])
                    ->sum('total_amount');

            $item['sum_amount'] = (int) $sumAmount;
        }

        return $array;
    }


    function updateStatus($id)
    {
        try {
            $debt = Debt::where('id', $id)->first();
            if ($debt) {
                $debt->status = Debt::STATUS_PAID;
                $debt->save();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getTotalFee($month = null, $year)
    {
        $query = Debt::query()->whereYear('updated_at', $year);

        if ($month) {
            $query->where('status', Debt::STATUS_PAID)
                ->whereMonth('updated_at', $month);
        } else {
            $query->where('status', Debt::STATUS_UNPAID);
        }
        return $query->sum('total_amount');
        // return $query->where('formality', '!=', 'R')->sum('total_amount');
    }

    public function getTotalMoney($month = null, $year)
    {
        $query = Debt::query()->whereYear('updated_at', $year);
        if ($month) {
            $query->where('status', Debt::STATUS_PAID)
                ->whereMonth('updated_at', $month);
        } else {
            $query->where('status', Debt::STATUS_UNPAID);
        }
        return $query->sum('total_money');
    }

    public function getBusinessMoney(int $businessId)
    {
        return BusinessMoney::where('business_id', $businessId)->get();
    }
}
