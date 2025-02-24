<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService extends BaseService
{
    public function datatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = ActivityLog::query();

        $recordsFiltered = $recordsTotal = $query->count();
        $result = $query
            ->latest()
            ->with('user')
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
}
