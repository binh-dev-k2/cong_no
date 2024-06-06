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
        $pageLength = $data['length'] ?? 10;
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
            ->orderBy('phone', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($skip)
            ->take($pageLength)
            ->get();

        $currentPhone = null;

        $debts->each(function ($debt) use (&$currentPhone, $data) {
            if ($currentPhone === $debt->phone) {
                $debt->sum_amount = null;
            } else {
                $currentPhone = $debt->phone;

                $sumAmount = $debt->status === Debt::STATUS_PAID ?
                    Debt::where('status', Debt::STATUS_PAID)
                    ->whereMonth('created_at', $data['month'])
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('total_amount') : Debt::where('status', Debt::STATUS_UNPAID)
                    ->where('phone', $debt->phone)
                    ->sum('total_amount');

                $debt->sum_amount = (int)$sumAmount;
            }
        });

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $debts
        ];
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

    public function getTotalMoney()
    {
        return Debt::where('status', Debt::STATUS_UNPAID)->sum('total_amount');
    }
}
