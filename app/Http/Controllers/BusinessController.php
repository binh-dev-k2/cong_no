<?php

namespace App\Http\Controllers;

use App\Services\BusinessService;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public $businessService;

    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    public function index()
    {
        return view('business.index');
    }

    public function datatable(Request $request)
    {
        $result = $this->businessService->datatable($request->all());

        return response()->json($result);
    }
}
