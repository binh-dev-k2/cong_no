<?php

namespace App\Services;

use App\Models\Debt;


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
            ->with(['bank'])
            ->take($pageLength)
            ->get();
        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $debts
        ];
    }

    
    // function checkDebt($id){
    //     $debt = Debt::find($id);
    //     if($debt->status == 0){
    //         return "Chưa thanh toán";
    //     }else{
    //         return "Đã thanh toán";
    //     }
    // }
}
