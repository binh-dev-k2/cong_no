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

        $recordsFiltered = $recordsTotal = $query->count();
        $result  = $query
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
        $result = Machine::create($data);
        return $result;
    }

    public function update(array $data, $id)
    {
        $result = Machine::where('id', $id)->update($data);
        return $result;
    }

    public function delete($id)
    {
        $result = Machine::where('id', $id)->delete();
        Business::where('machine_id', $id)->update(['machine_id' => null]);
        return $result;
    }
}
