<?php

namespace App\Services;

use App\Models\Debt;
use Illuminate\Support\Facades\Log;

class DebtService
{
    function datatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 10;
        $skip = ($pageNumber - 1) * $pageLength;
        $query = Debt::query();
        if (isset($data['search'])) {
            $search = $data['search'];
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('card_number', 'like', "%{$search}%");
        }
        $query->orderBy('id', 'desc');
        $recordsFiltered = $recordsTotal = $query->count();
        $debts = $query->skip($skip)
            ->with(['card.bank'])
            ->take($pageLength)
            ->get();
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
}
