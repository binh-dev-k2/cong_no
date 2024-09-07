<?php

namespace App\Http\Controllers;

use App\Http\Requests\Business\BusinessRequest;
use App\Models\Business;
use App\Models\Setting;
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
        $businessNote = Setting::firstOrCreate(['key' => 'business_note'], ['key' => 'business_note', 'value' => '']);
        return view('business.index', compact('businessNote'));
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

    public function update(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->businessService->update($data);
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

    public function updateBusinessMoney(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->businessService->updateBusinessMoney($data);
        return jsonResponse($result ? 0 : 1, $result ? 'Thành công' : 'Thất bại');
    }

    public function delete(BusinessRequest $request)
    {
        $data = $request->validated();
        $result = $this->businessService->delete($data['id']);
        return jsonResponse($result ? 0 : 1);
    }

    public function editSetting()
    {
        $min = Setting::where('key', 'business_min')->first()?->value;
        $max = Setting::where('key', 'business_max')->first()?->value;
        return view('business.modal.edit-setting', compact('min', 'max'));
    }

    public function updateSetting(Request $request)
    {
        $data = $request->all();
        $result = $this->businessService->updateSetting($data);
        return jsonResponse($result ? 0 : 1);
    }


    public function updateNote(Request $request)
    {
        $data = $request->all();
        $result = $this->businessService->updateNote($data);
        return jsonResponse($result ? 0 : 1);
    }
}
