<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogActivityTrait
{
    public static function bootLogActivityTrait()
    {
        // Lắng nghe các sự kiện model
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    protected function logActivity($event)
    {
        // Lấy user hiện tại (nếu có)
        $userId = Auth::id();

        if ($userId) {
            // Ghi log vào bảng activity_logs
            ActivityLog::create([
                'table_name' => $this->getTable(),
                'table_id' => $this->id,
                'user_id' => $userId,
                'log' => json_encode([
                    'event' => $event,
                    'attributes' => $this->getOriginal(),
                    'changes' => $this->getChanges(),
                ]),
            ]);
        }
    }
}
