<?php

namespace App\Services;

use App\Models\Business;

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

    public function complete(array $data)
    {
        return true;
    }

    public function updatePayExtra($data)
    {
        return Business::where('id', $data['id'])->update(['pay_extra' => $data['pay_extra']]);
    }

    public function getBusinessMoney(int $id)
    {
        return Business::where('id', $id)->with(['money'])->first();
    }
}
