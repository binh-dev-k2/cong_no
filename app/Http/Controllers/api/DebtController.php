<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DebtService;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public $debt_service;
    public function __construct(DebtService $debt_service) {
        $this->debt_service = $debt_service;
    }

    public function getAllDebt(Request $request){
        $data = $this->debt_service->filterDataTable($request->all());
        return response()->json($data);
    }
}
