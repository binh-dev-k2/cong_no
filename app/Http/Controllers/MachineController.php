<?php

namespace App\Http\Controllers;

use App\Http\Requests\Machine\MachineRequest;
use App\Services\MachineService;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public $machineService;

    public function __construct(MachineService $machineService)
    {
        $this->machineService = $machineService;
    }

    public function index()
    {
        return view('machine.main');
    }

    public function datatable(Request $request)
    {
        $result = $this->machineService->datatable($request->all());
        return response()->json($result);
    }

    public function store(MachineRequest $request)
    {
        $data = $request->validated();
        $result = $this->machineService->store($data);
        return jsonResponse($result ? 0 : 1);
    }

    public function update(MachineRequest $request)
    {
        $data = $request->validated();
        $result = $this->machineService->update($data, $id);
        return jsonResponse($result ? 0 : 1);
    }

    public function delete(MachineRequest $request)
    {
        $data = $request->validated();
        $result = $this->machineService->delete($data['id']);
        return jsonResponse($result ? 0 : 1);
    }
}
