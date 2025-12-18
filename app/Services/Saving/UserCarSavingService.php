<?php

namespace App\Services\Saving;

use App\Services\BaseSavingService;
use App\Models\UserCar;

class UserCarSavingService extends BaseSavingService
{
    public string $modelName = UserCar::class;

    public function prepareArray($params)
    {
        // Set default status for new user cars
        if (!isset($params['id'])) {
            $params['status'] = 'pending';
            $params['is_active'] = $params['is_active'] ?? false;
        }

        return $params;
    }

    public function validate($params)
    {
        // Required fields validation
        $required = ['user_id', 'brand', 'model', 'year', 'fuel_type', 'transmission', 'user_expected_price'];
        foreach ($required as $field) {
            if (!isset($params[$field]) || empty($params[$field])) {
                throw new \Exception("الحقل {$field} مطلوب");
            }
        }

        // Year validation
        if (isset($params['year'])) {
            $currentYear = date('Y');
            if ($params['year'] < 1990 || $params['year'] > $currentYear + 1) {
                throw new \Exception('سنة الصنع غير صحيحة');
            }
        }

        // Price validation
        if (isset($params['user_expected_price']) && $params['user_expected_price'] <= 0) {
            throw new \Exception('السعر المتوقع يجب أن يكون أكبر من صفر');
        }

        // Fair price validation (admin only)
        if (isset($params['fair_price']) && $params['fair_price'] !== null && $params['fair_price'] <= 0) {
            throw new \Exception('السعر العادل يجب أن يكون أكبر من صفر');
        }
    }

    public function saveRelatedData($model, $params)
    {
        // Handle image URLs if provided
        if (isset($params['images']) && is_array($params['images'])) {
            // Filter out empty URLs and validate URLs
            $validImages = [];
            foreach ($params['images'] as $imageUrl) {
                if (!empty(trim($imageUrl))) {
                    // Basic URL validation
                    if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                        $validImages[] = trim($imageUrl);
                    }
                }
            }
            $model->images = $validImages;
            $model->save();
        }
    }

    public function afterSave($model, $params)
    {
        // If this car is set as active, deactivate other user cars
        if (isset($params['is_active']) && $params['is_active']) {
            UserCar::where('user_id', $model->user_id)
                ->where('id', '!=', $model->id)
                ->update(['is_active' => false]);
        }

        // If fair_price is set, update status to 'priced'
        if (isset($params['fair_price']) && $params['fair_price'] !== null) {
            if ($model->status === 'pending') {
                $model->status = 'priced';
                $model->save();
            }
        }
    }
}
