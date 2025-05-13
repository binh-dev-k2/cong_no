<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Machine;

class MachineService
{
    public function datatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Machine::query();

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        if (isset($data['year'])) {
            $query->withSum(['businessFees' => function ($subQuery) use ($data) {
                $subQuery->where('year', $data['year']);
                if (isset($data['month'])) {
                    $subQuery->where('month', $data['month']);
                }
            }], 'fee');
            $query->withSum(['businessFees' => function ($subQuery) use ($data) {
                $subQuery->where('year', $data['year']);
                if (isset($data['month'])) {
                    $subQuery->where('month', $data['month']);
                }
            }], 'total_money');
        } else {
            $query->withSum('businessFees', 'fee');
            $query->withSum('businessFees', 'total_money');
        }

        $recordsFiltered = $recordsTotal = $query->count();
        $result = $query
            ->skip($skip)
            ->take($pageLength)
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $result
        ];
    }

    public function calculateTotalMoney($data)
    {
        $query = Machine::query();

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        if (isset($data['year'])) {
            $query->withSum(['businessFees' => function ($subQuery) use ($data) {
                $subQuery->where('year', $data['year']);
                if (isset($data['month'])) {
                    $subQuery->where('month', $data['month']);
                }
            }], 'fee');
            $query->withSum(['businessFees' => function ($subQuery) use ($data) {
                $subQuery->where('year', $data['year']);
                if (isset($data['month'])) {
                    $subQuery->where('month', $data['month']);
                }
            }], 'total_money');
        } else {
            $query->withSum('businessFees', 'fee');
            $query->withSum('businessFees', 'total_money');
        }

        $machines = $query->get();
        logger($machines);
        $totalMoney = (float)($machines->sum('business_fees_sum_total_money') ?? 0);
        $totalFee = (float)($machines->sum('business_fees_sum_fee') ?? 0);

        return ['totalMoney' => $totalMoney, 'totalFee' => $totalFee];
    }

    public function store(array $data)
    {
        $result = Machine::create($data);
        return $result;
    }

    public function update(array $data, $id)
    {
        $model = Machine::findOrFail($id);
        $model->update($data);
        return $model;
    }

    // public function delete($id)
    // {
    //     $result = Machine::findOrFail($id)->delete();
    //     Business::where('machine_id', $id)->update(['machine_id' => null]);
    //     return $result;
    // }
}
