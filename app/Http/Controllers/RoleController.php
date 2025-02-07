<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\RoleRequest;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        $permissions = $this->roleService->getPermissions();
        return view('role.index', compact('permissions'));
    }

    public function datatable(Request $request)
    {
        $result = $this->roleService->datatable($request->all());
        return response()->json($result);
    }

    public function store(RoleRequest $request)
    {
        $data = $request->validated();
        $result = $this->roleService->store($data);
        return jsonResponse($result ? 0 : 1);
    }

    public function update(RoleRequest $request)
    {
        $data = $request->validated();
        $result = $this->roleService->update($data, $data['id']);
        return jsonResponse($result ? 0 : 1);
    }

    public function delete(RoleRequest $request)
    {
        $data = $request->validated();
        $result = $this->roleService->delete($data['id']);
        return jsonResponse($result ? 0 : 1);
    }
}
