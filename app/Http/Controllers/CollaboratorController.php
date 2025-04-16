<?php

namespace App\Http\Controllers;

use App\Http\Requests\Collaborator\CollaboratorRequest;
use App\Services\CollaboratorService;

use Illuminate\Http\Request;

class CollaboratorController extends Controller
{
    public $collaboratorService;

    public function __construct(CollaboratorService $collaboratorService)
    {
        $this->collaboratorService = $collaboratorService;
    }

    public function index()
    {
        return view('collaborator.main');
    }

    public function datatable(Request $request)
    {
        $result = $this->collaboratorService->datatable($request->all());
        return response()->json($result);
    }

    public function store(CollaboratorRequest $request)
    {
        $data = $request->validated();
        $result = $this->collaboratorService->store($data);
        return jsonResponse($result ? 0 : 1);
    }

    public function update(CollaboratorRequest $request)
    {
        $data = $request->validated();
        $result = $this->collaboratorService->update($data, $data['id']);
        return jsonResponse($result ? 0 : 1);
    }

    public function delete(CollaboratorRequest $request)
    {
        $data = $request->validated();
        $result = $this->collaboratorService->delete($data['id']);
        return jsonResponse($result ? 0 : 1);
    }
}
