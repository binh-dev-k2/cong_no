<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Collaborator;

class CollaboratorService
{
    public function datatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Collaborator::query();

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
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

    public function store(array $data)
    {
        $result = Collaborator::create($data);
        return $result;
    }

    public function update(array $data, $id)
    {
        $model = Collaborator::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $result = Collaborator::findOrFail($id)->delete();
        Business::where('collaborator_id', $id)->update(['collaborator_id' => null]);
        return $result;
    }
}
