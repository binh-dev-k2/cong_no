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

    function getDonutChartData()
    {
        $data = $this->dashBoardService->getDounutChartData();
        return response()->json($data);
    }

    function getProcessData(){
        $data = $this->dashBoardService->getProcessData();
        return response()->json($data);
    }

    function getTotalBusiness(){
        $data = $this->dashBoardService->getTotalBusiness();
        return response()->json($data);
    }
}
