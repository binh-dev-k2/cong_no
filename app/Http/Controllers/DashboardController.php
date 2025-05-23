<?php

namespace App\Http\Controllers;

use App\Services\DashBoardService;
use App\Services\DebtService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashBoardService;
    /**
     * @param $dashBoardService
     */
    public function __construct(DashBoardService $dashBoardService)
    {
        $this->dashBoardService = $dashBoardService;
    }

    public function index()
    {
        return view('dashboard');
    }

    public function getTotalInvestment()
    {
        $data = $this->dashBoardService->getTotalInvestment();
        return response()->json($data);
    }

    public function updateTotalInvestment(Request $request)
    {
        $result = $this->dashBoardService->updateTotalInvestment($request->input());
        return jsonResponse($result ? 0 : 1);
    }

    public function getChartCustomer()
    {
        $data = $this->dashBoardService->getChartCustomer();
        return response()->json($data);
    }

    public function getTotalDebit()
    {
        $data = $this->dashBoardService->getTotalDebit();
        return response()->json($data);
    }

    public function getTotalBusiness()
    {
        $data = $this->dashBoardService->getTotalBusiness();
        return response()->json($data);
    }

    public function getCardExpired(Request $request)
    {
        $data = $this->dashBoardService->getCardExpired($request->input());
        return response()->json($data);
    }

    public function getMachineFee(Request $request)
    {
        $data = $this->dashBoardService->getMachineFee($request->input());
        return response()->json($data);
    }
}
