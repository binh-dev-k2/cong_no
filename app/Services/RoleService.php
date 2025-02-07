<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService extends BaseService
{
    public function datatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Role::query();

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->where('name', 'like', "%{$search}%");
        }

        $recordsFiltered = $recordsTotal = $query->count();
        $result = $query
            ->with('permissions')
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
        $result = Role::create(['name' => $data['name']]);
        $result->syncPermissions($data['permissions']);
        return $result;
    }

    public function update(array $data, $id)
    {
        $result = Role::find($id);
        $result->update($data);
        $result->syncPermissions($data['permissions']);
        return $result;
    }

    public function delete($id)
    {
        $result = Role::find($id);
        $result->delete();

        // xoa cache
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return $result;
    }

    public function getPermissions()
    {
        return Permission::all();
    }
}
