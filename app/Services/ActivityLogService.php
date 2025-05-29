<?php

namespace App\Services;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ActivityLogService extends BaseService
{
    public function datatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = ActivityLog::query();

        // Add search functionality
        if (!empty($data['search'])) {
            $search = $data['search'];
            $query->where(function($q) use ($search) {
                $q->where('log', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Add event filter
        if (!empty($data['event'])) {
            $query->where('log', 'LIKE', '%"event":"' . $data['event'] . '"%');
        }

        // Add date filter
        if (!empty($data['date'])) {
            $query->whereDate('created_at', $data['date']);
        }

        $recordsFiltered = $recordsTotal = $query->count();
        $result = $query
            ->latest()
            ->with('user')
            ->skip($skip)
            ->take($pageLength)
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $result
        ];
    }

    /**
     * Get activity log statistics
     */
    public function getStatistics(): array
    {
        $total = ActivityLog::count();
        $today = ActivityLog::whereDate('created_at', Carbon::today())->count();
        $week = ActivityLog::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        // Calculate log size (estimate based on log entries)
        $avgLogSize = 0.5; // KB per log entry (rough estimate)
        $logSizeMB = round(($total * $avgLogSize) / 1024, 2);

        // Also include Laravel log files size
        $laravelLogSize = $this->calculateLaravelLogSize();
        $totalLogSize = $logSizeMB + $laravelLogSize;

        return [
            'total' => $total,
            'today' => $today,
            'week' => $week,
            'logSize' => round($totalLogSize, 2)
        ];
    }

    /**
     * Calculate Laravel log files size in MB
     */
    private function calculateLaravelLogSize(): float
    {
        $logPath = storage_path('logs');
        $totalSize = 0;

        if (is_dir($logPath)) {
            $files = glob($logPath . '/*.log');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $totalSize += filesize($file);
                }
            }
        }

        return round($totalSize / (1024 * 1024), 2); // Convert to MB
    }

    /**
     * Cleanup old activity logs and Laravel log files
     */
    public function cleanupOldLogs(): array
    {
        try {
            $thirtyDaysAgo = Carbon::now()->subDays(30);

            // Count records before deletion
            $oldLogsCount = ActivityLog::where('created_at', '<', $thirtyDaysAgo)->count();

            // Calculate approximate space that will be freed
            $freedSpaceFromDB = round(($oldLogsCount * 0.5) / 1024, 2); // MB

            // Delete old activity logs from database
            ActivityLog::where('created_at', '<', $thirtyDaysAgo)->delete();

            $totalFreedSpace = $freedSpaceFromDB;

            return [
                'success' => true,
                'deleted_count' => $oldLogsCount,
                'freed_space' => round($totalFreedSpace, 2),
                'message' => "Đã xóa {$oldLogsCount} bản ghi log cũ và giải phóng {$totalFreedSpace} MB"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi dọn dẹp log: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Translate event names to Vietnamese
     */
    private function translateEvent(string $event): string
    {
        $translations = [
            'created' => 'Tạo mới',
            'updated' => 'Cập nhật',
            'deleted' => 'Xóa'
        ];

        return $translations[$event] ?? $event;
    }
}
