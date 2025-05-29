<?php

namespace App\Services;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class BusinessSettingService extends BaseService
{
    /**
     * Get all business settings grouped by type and key
     *
     * @return array
     */
    public function getSettingsGrouped(): array
    {
        $moneySettings = BusinessSetting::where('type', 'MONEY')
            ->orderBy('value')
            ->get()
            ->groupBy('key');

        $percentSettings = BusinessSetting::where('type', 'PERCENT')
            ->orderBy('value')
            ->get()
            ->groupBy('key');

        return [
            'money' => $moneySettings,
            'percent' => $percentSettings,
            'count' => BusinessSetting::count()
        ];
    }

    /**
     * Update business settings with transaction safety
     *
     * @param array $data
     * @return array
     */
    public function updateSettings(array $data): array
    {
        $validation = $this->validateSettingsData($data);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'errors' => $validation['errors']
            ];
        }

        try {
            DB::beginTransaction();

            // Delete all existing settings first
            BusinessSetting::query()->delete();

            // Insert new settings
            $settingsToInsert = [];
            foreach ($data as $setting) {
                $settingsToInsert[] = [
                    'type' => $setting['type'],
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            if (!empty($settingsToInsert)) {
                BusinessSetting::insert($settingsToInsert);
            }

            DB::commit();
            return ['success' => true];

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('BusinessSetting update failed: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'errors' => ['Có lỗi xảy ra khi cập nhật cài đặt: ' . $th->getMessage()]
            ];
        }
    }

    /**
     * Validate settings data
     *
     * @param array $data
     * @return array
     */
    private function validateSettingsData(array $data): array
    {
        $errors = [];

        if (empty($data)) {
            $errors[] = 'Vui lòng nhập thông tin cài đặt';
            return [
                'valid' => false,
                'errors' => $errors
            ];
        }

        foreach ($data as $index => $setting) {
            if (empty($setting['type']) || !in_array($setting['type'], ['MONEY', 'PERCENT'])) {
                $errors[] = "Loại cài đặt không hợp lệ ở dòng " . ($index + 1);
            }

            if (!isset($setting['key']) || !is_numeric($setting['key']) || $setting['key'] <= 0) {
                $errors[] = "Mốc phải là số dương ở dòng " . ($index + 1);
            }

            if (!isset($setting['value']) || !is_numeric($setting['value']) || $setting['value'] <= 0) {
                $errors[] = "Giá trị phải là số dương ở dòng " . ($index + 1);
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Get settings for a specific type
     *
     * @param string $type
     * @return Collection
     */
    public function getSettingsByType(string $type): Collection
    {
        return BusinessSetting::where('type', $type)
            ->orderBy('key')
            ->orderBy('value')
            ->get();
    }

    /**
     * Get settings for a specific key
     *
     * @param mixed $key
     * @return Collection
     */
    public function getSettingsByKey($key): Collection
    {
        return BusinessSetting::where('key', $key)
            ->orderBy('value')
            ->get();
    }

    /**
     * Delete settings by type and key
     *
     * @param string $type
     * @param mixed $key
     * @return bool
     */
    public function deleteSettingsByTypeAndKey(string $type, $key): bool
    {
        try {
            return BusinessSetting::where('type', $type)
                ->where('key', $key)
                ->delete();
        } catch (\Throwable $th) {
            Log::error('Failed to delete settings: ' . $th->getMessage());
            return false;
        }
    }

    /**
     * Create or update a single setting
     *
     * @param array $settingData
     * @return BusinessSetting|false
     */
    public function createOrUpdateSetting(array $settingData)
    {
        try {
            return BusinessSetting::updateOrCreate(
                [
                    'type' => $settingData['type'],
                    'key' => $settingData['key'],
                    'value' => $settingData['value']
                ],
                $settingData
            );
        } catch (\Throwable $th) {
            Log::error('Failed to create/update setting: ' . $th->getMessage());
            return false;
        }
    }
}
