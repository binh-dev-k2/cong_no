<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;

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
}
