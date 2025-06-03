<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgencyService;
use App\Http\Requests\Agency\AgencyRequest;
use App\Http\Requests\Agency\AgencyBusinessRequest;

class AgencyController extends Controller
{
    public $agencyService;

    public function __construct(AgencyService $agencyService)
    {
        $this->agencyService = $agencyService;
    }

    /**
     * Display the agencies management page.
     */
    public function indexAgency()
    {
        return view('agency.index');
    }

    public function indexAgencyBusiness()
    {
        return view('agency.business.index');
    }

    public function indexAgencyAnalytics()
    {
        return view('agency.analytics.index');
    }

    /**
     * Get all agencies with their business count.
     */
    public function getAgencies(Request $request)
    {
        try {
            $agencies = $this->agencyService->getAgenciesForUser($request->search ?? null);
            return jsonResponse(0, $agencies);
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi tải danh sách đại lý: ' . $e->getMessage());
        }
    }

    /**
     * Get agency statistics.
     */
    public function getStatistics()
    {
        try {
            $statistics = $this->agencyService->getAgencyStatisticsForUser();
            return jsonResponse(0, $statistics);
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi tải thống kê: ' . $e->getMessage());
        }
    }

    /**
     * Store a new agency.
     */
    public function store(AgencyRequest $request)
    {
        try {
            $data = $request->validated();
            $result = $this->agencyService->createAgency($data);
            return jsonResponse($result ? 0 : 1, $result ? 'Tạo đại lý thành công' : 'Tạo đại lý thất bại');
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi tạo đại lý: ' . $e->getMessage());
        }
    }

    /**
     * Update an agency.
     */
    public function update(AgencyRequest $request)
    {
        try {
            $data = $request->validated();
            $result = $this->agencyService->updateAgency($data['id'], $data);
            return jsonResponse($result ? 0 : 1, $result ? 'Cập nhật đại lý thành công' : 'Cập nhật đại lý thất bại');
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi cập nhật đại lý: ' . $e->getMessage());
        }
    }

    /**
     * Delete an agency.
     */
    // public function destroy(AgencyRequest $request)
    // {
    //     try {
    //         $data = $request->validated();
    //         $result = $this->agencyService->deleteAgency($data['id']);
    //         return jsonResponse($result ? 0 : 1, $result ? 'Xóa đại lý thành công' : 'Xóa đại lý thất bại');
    //     } catch (\InvalidArgumentException $e) {
    //         return jsonResponse(1, $e->getMessage());
    //     } catch (\Exception $e) {
    //         return jsonResponse(1, 'Lỗi khi xóa đại lý: ' . $e->getMessage());
    //     }
    // }

    /**
     * Get agency businesses.
     */
    public function getAgencyBusinesses(Request $request)
    {
        try {
            $agencyId = $request->query('agency_id');
            if (!$agencyId) {
                return jsonResponse(1, 'ID đại lý là bắt buộc');
            }

            $businesses = $this->agencyService->getAgencyBusinesses($agencyId);
            return jsonResponse(0, $businesses);
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi tải danh sách nghiệp vụ: ' . $e->getMessage());
        }
    }

    /**
     * Get agency business details by ID.
     */
    public function getAgencyBusinessById(Request $request)
    {
        try {
            $businessId = $request->query('business_id');
            if (!$businessId) {
                return jsonResponse(1, 'ID nghiệp vụ là bắt buộc');
            }

            $business = $this->agencyService->getAgencyBusinessById($businessId);
            return jsonResponse(0, $business);
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi tải thông tin nghiệp vụ: ' . $e->getMessage());
        }
    }

    /**
     * Store a new agency business.
     */
    public function storeAgencyBusiness(AgencyBusinessRequest $request)
    {
        try {
            $data = $request->validated();

            // Check if user can manage this agency
            if (!$this->agencyService->canManageAgencyBusiness($data['agency_id'])) {
                return jsonResponse(1, 'Bạn không có quyền thêm nghiệp vụ cho đại lý này');
            }

            $result = $this->agencyService->createAgencyBusiness($data);
            return jsonResponse($result ? 0 : 1, $result ? 'Tạo nghiệp vụ thành công' : 'Tạo nghiệp vụ thất bại');
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi tạo nghiệp vụ: ' . $e->getMessage());
        }
    }

    /**
     * Update an agency business.
     */
    public function updateAgencyBusiness(AgencyBusinessRequest $request)
    {
        try {
            $data = $request->validated();

            // Check if user can manage this agency
            if (!$this->agencyService->canManageAgencyBusiness(businessId: $data['business_id'])) {
                return jsonResponse(1, 'Bạn không có quyền cập nhật nghiệp vụ của đại lý này');
            }

            // Include file uploads if present
            if ($request->hasFile('image_front')) {
                $data['image_front'] = $request->file('image_front');
            }
            if ($request->hasFile('image_summary')) {
                $data['image_summary'] = $request->file('image_summary');
            }

            $result = $this->agencyService->updateAgencyBusiness($data['business_id'], $data);
            return jsonResponse($result ? 0 : 1, $result ? 'Cập nhật nghiệp vụ thành công' : 'Cập nhật nghiệp vụ thất bại');
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi cập nhật nghiệp vụ: ' . $e->getMessage());
        }
    }

    /**
     * Delete an agency business.
     */
    public function destroyAgencyBusiness(AgencyBusinessRequest $request)
    {
        try {
            $data = $request->validated();

            // Check if user can manage this agency
            if (!$this->agencyService->canManageAgencyBusiness(businessId: $data['business_id'])) {
                return jsonResponse(1, 'Bạn không có quyền xóa nghiệp vụ của đại lý này');
            }

            $result = $this->agencyService->deleteAgencyBusiness($data['business_id']);
            return jsonResponse($result ? 0 : 1, $result ? 'Xóa nghiệp vụ thành công' : 'Xóa nghiệp vụ thất bại');
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi xóa nghiệp vụ: ' . $e->getMessage());
        }
    }

    /**
     * Complete an agency business.
     */
    public function completeAgencyBusiness(AgencyBusinessRequest $request)
    {
        try {
            $data = $request->validated();

            // Check if user can manage this agency
            if (!$this->agencyService->canManageAgencyBusiness(businessId: $data['business_id'])) {
                return jsonResponse(1, 'Bạn không có quyền đánh dấu hoàn thành nghiệp vụ của đại lý này');
            }

            $result = $this->agencyService->completeAgencyBusiness($data['business_id']);
            return jsonResponse($result ? 0 : 1, $result ? 'Đánh dấu hoàn thành nghiệp vụ thành công' : 'Đánh dấu hoàn thành nghiệp vụ thất bại');
        } catch (\InvalidArgumentException $e) {
            return jsonResponse(1, $e->getMessage());
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi đánh dấu hoàn thành nghiệp vụ: ' . $e->getMessage());
        }
    }

    /**
     * Get all machines for dropdown.
     */
    public function getMachines()
    {
        try {
            $machines = $this->agencyService->getAllMachines();
            return jsonResponse(0, $machines);
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi tải danh sách máy: ' . $e->getMessage());
        }
    }

    /**
     * Get machines for specific agency.
     */
    public function getMachinesForAgency(Request $request)
    {
        try {
            $agencyId = $request->query('agency_id');
            if (!$agencyId) {
                return jsonResponse(1, 'ID đại lý là bắt buộc');
            }

            // Check if user can manage this agency
            if (!$this->agencyService->canManageAgencyBusiness(agencyId: $agencyId)) {
                return jsonResponse(1, 'Bạn không có quyền tải danh sách máy của đại lý này');
            }

            $machines = $this->agencyService->getMachinesForAgency($agencyId);
            return jsonResponse(0, $machines);
        } catch (\Exception $e) {
            return jsonResponse(1, 'Lỗi khi tải danh sách máy: ' . $e->getMessage());
        }
    }

    /**
     * Get completed businesses datatable.
     */
    public function getCompletedBusinessesDatatable(Request $request)
    {
        try {
            $result = $this->agencyService->getCompletedBusinessesDatatable($request);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi khi tải dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAgencyAnalyticsDatatable(Request $request)
    {
        try {
            $result = $this->agencyService->getAgencyAnalyticsDatatable($request);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi khi tải dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }
}
