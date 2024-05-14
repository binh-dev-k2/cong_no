<?php

namespace App\Http\Controllers;

use App\Http\Requests\Business\BusinessRequest;
use App\Services\CardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public $cardService;

    public function __construct()
    {
        $this->cardService = $this->cardServiceProperty();
    }

    public function cardServiceProperty()
    {
        return app(CardService::class);
    }

    public function index()
    {
        return view('business.index');
    }

    public function datatable(Request $request): JsonResponse
    {
        $result = $this->cardService->filterDatatableBusiness($request->all());
        return response()->json($result);
    }

    public function complete(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->cardService->businessComplete($data['id']);
        return jsonResponse($result ? 0 : 1);
    }

    public function updatePayExtra(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->cardService->businessUpdatePayExtra($data);
        return jsonResponse($result ? 0 : 1);
    }

    public function viewMoney(BusinessRequest $request)
    {
        $data = $request->validated();
        $money = $this->cardService->businessGetMoney($data['id']);
        return view('business.modal.money', compact('money'));
    }

    public function updateMoneyNote(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->cardService->businessUpdateMoneyNote($data);
        return jsonResponse($result ? 0 : 1, $result ? 'Thành công' : 'Thất bại');
    }
}
