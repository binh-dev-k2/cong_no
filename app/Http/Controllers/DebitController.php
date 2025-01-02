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

    public function index()
    {
        return view('debit.index');
    }

    public function getTotalMoney(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');
        $totalMoney = $this->debtService->getTotalMoney($month, $year);
        $totalFee = $this->debtService->getTotalFee($month, $year);
        return jsonResponse(0, ['totalMoney' => $totalMoney, 'totalFee' => $totalFee]);
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

        public function viewMoney(Request $request)
    {
        $businessId = $request->input('business_id');
        $money = $this->debtService->getBusinessMoney($businessId);
        return view('debit.modal.money', compact('money'));
    }
}
