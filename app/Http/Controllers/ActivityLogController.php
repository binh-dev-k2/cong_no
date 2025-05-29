<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ActivityLogController extends Controller
{
    public $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        return view('activity-log.index');
    }

    public function datatable(Request $request)
    {
        $result = $this->activityLogService->datatable($request->all());
        return response()->json($result);
    }

    /**
     * Get activity log statistics
     */
    public function statistics()
    {
        try {
            $stats = $this->activityLogService->getStatistics();
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không thể tải thống kê: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cleanup old activity logs (older than 30 days)
     */
    public function cleanup()
    {
        try {
            $result = $this->activityLogService->cleanupOldLogs();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'deleted_count' => $result['deleted_count'],
                    'freed_space' => $result['freed_space'],
                    'message' => 'Dọn dẹp log thành công'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Có lỗi xảy ra khi dọn dẹp log'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}
