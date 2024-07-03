<?php

namespace App\Services;

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

        if (isset($data['month'])) {
            $query->where('status', Debt::STATUS_PAID)
                ->whereMonth('created_at', $data['month'])
                ->whereYear('created_at', Carbon::now()->year);
        } else {
            $query->where('status', Debt::STATUS_UNPAID);
        }

        $recordsFiltered = $recordsTotal = $query->count();
        $debts = $query
            ->orderBy('created_at', 'desc')
            // ->orderBy('phone', 'asc')
            // ->skip($skip)
            // ->take($pageLength)
            ->get()
            ->toArray();

        $sortedDebts = $this->customSort($debts, $skip + $pageLength);

        $paginatedDebts = array_slice($sortedDebts, $skip, $pageLength);
        $this->calculateSumAmount($paginatedDebts, $data['month']);

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

    public function calculateSumAmount(&$array, $month)
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
                ->whereYear('created_at', Carbon::now()->year)
                ->where('phone', $item['phone'])
                ->sum('total_amount')
                : Debt::where('status', Debt::STATUS_UNPAID)
                ->where('phone', $item['phone'])
                ->sum('total_amount');

            $item['sum_amount'] = (int)$sumAmount;
        }

        return $array;
    }


    function updateStatus($id)
    {
        try {
            return Debt::where('id', $id)->update(['status' => Debt::STATUS_PAID]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getTotalFee($month = null)
    {
        $query = Debt::query()->where('status', Debt::STATUS_UNPAID);
        if ($month) {
            $query->whereMonth('created_at', $month)
                ->whereYear('created_at', Carbon::now()->year);
        }
        return $query->where('formality', '!=', 'R')->sum('total_amount');
    }

    public function getTotalMoney($month = null)
    {
        $query = Debt::query()->where('status', Debt::STATUS_UNPAID);
        if ($month) {
            $query->whereMonth('created_at', $month)
                ->whereYear('created_at', Carbon::now()->year);
        }
        return $query->sum('total_amount');
    }
}
