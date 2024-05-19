<?php

namespace App\Http\Controllers;

use App\Services\DebtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DebitController extends Controller
{
    public $debtService;

    public function __construct(DebtService $debtService)
    {
        $this->debtService = $debtService;
    }

    function index()
    {
        $totalMoney = $this->debtService->getTotalMoney();
        return view('debit.index', compact('totalMoney'));
    }

    public function showAllDebits(Request $request): JsonResponse
    {
        $result = $this->debtService->datatable($request->all());
        return response()->json($result);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $result = $this->debtService->updateStatus($data);
        return jsonResponse($result ? 0 : 1);
    }
}
