<?php

namespace App\Http\Controllers;

use App\Http\Requests\Business\BusinessRequest;
use App\Models\Business;
use App\Services\BusinessService;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public $businessService;

    public function __construct()
    {
        $this->businessService = $this->businessServiceProperty();
    }

    public function businessServiceProperty()
    {
        return app(BusinessService::class);
    }

    public function index()
    {
        return view('business.index');
    }

    public function datatable(Request $request)
    {
        $result = $this->businessService->filterDatatable($request->all());
        return response()->json($result);
    }

    public function store(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->businessService->store($data);
        return jsonResponse($result ? 0 : 1);
    }

    public function complete(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->businessService->complete($data['id']);
        return jsonResponse($result ? 0 : 1);
    }

    public function updatePayExtra(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->businessService->updatePayExtra($data);
        return jsonResponse($result ? 0 : 1);
    }

    // public function viewMoney(BusinessRequest $request)
    // {
    //     $data = $request->validated();
    //     $money = $this->businessService->getBusinessMoney($data['id']);
    //     return view('business.modal.money', compact('money'));
    // }

    public function updateBusinessMoney(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->businessService->updateBusinessMoney($data);
        return jsonResponse($result ? 0 : 1, $result ? 'Thành công' : 'Thất bại');
    }
}
