<?php

namespace App\Http\Controllers;

use App\Services\DashBoardService;
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

    function index()
    {
        return view('dashboard');
    }

    function getChartCustomer()
    {
        $data = $this->dashBoardService->getChartCustomer();
        return response()->json($data);
    }

    function getTotalDebit(){
        $data = $this->dashBoardService->getTotalDebit();
        return response()->json($data);
    }

    function getTotalBusiness(){
        $data = $this->dashBoardService->getTotalBusiness();
        return response()->json($data);
    }

    public function getCardExpired(Request $request){
        $data = $this->dashBoardService->getCardExpired($request->input());
        return response()->json($data);
    }
}
