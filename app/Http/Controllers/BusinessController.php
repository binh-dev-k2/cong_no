<?php

namespace App\Http\Controllers;

use App\Http\Requests\Business\BusinessRequest;
use App\Models\BusinessSetting;
use App\Models\Machine;
use App\Models\Collaborator;
use App\Models\Setting;
use App\Services\BusinessService;
use App\Services\BusinessSettingService;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public $businessService;
    public $businessSettingService;

    public function __construct()
    {
        $this->businessService = app(BusinessService::class);
        $this->businessSettingService = app(BusinessSettingService::class);
    }

    public function index()
    {
        $businessNote = Setting::firstOrCreate(['key' => 'business_note'], ['key' => 'business_note', 'type' => 'business_note', 'value' => '']);
        $businessMoneys = BusinessSetting::get()->groupBy(['type', 'key']);
        $machines = Machine::all();
        $collaborators = Collaborator::all();
        return view('business.index', compact('businessNote', 'businessMoneys', 'machines', 'collaborators'));
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
        try {
            // Use the new BusinessSettingService to get data
            return view('business.modal.edit-setting');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra khi tải cài đặt: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSetting(BusinessRequest $request)
    {
        try {
            $data = $request->validated();
            $result = $this->businessSettingService->updateSettings($data);

            if ($result['success']) {
                return jsonResponse(0, 'Cập nhật cài đặt thành công');
            } else {
                return jsonResponse(1, $result['errors']);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('BusinessController updateSetting error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return jsonResponse(1, ['Có lỗi xảy ra khi xử lý yêu cầu: ' . $e->getMessage()]);
        }
    }

    public function updateNote(Request $request)
    {
        $data = $request->all();
        $result = $this->businessService->updateNote($data);
        return jsonResponse($result ? 0 : 1);
    }
}
