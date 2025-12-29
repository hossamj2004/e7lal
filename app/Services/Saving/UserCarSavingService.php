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
        } else {
            // For updates, if fair_price is being set, activate this car
            if (isset($params['fair_price']) && $params['fair_price'] !== null) {
                $params['is_active'] = true;
                $params['status'] = 'priced';
            }
        }

        if(isset($params['ignore_validations'])) {
            if(!isset($params['fuel_type'] ))
                $params['fuel_type'] = 'petrol';
            if(!isset($params['transmission'] ))
                $params['transmission'] = 'manual';
        }
        return $params;
    }

    public function validate($params)
    {

        // For updates (when id is provided), only validate the fields being updated
        if (isset($params['id'])) {
            // For updates, we only validate fair_price when pricing
            if (isset($params['fair_price'])) {
                if ($params['fair_price'] <= 0) {
                    throw new \Exception('السعر العادل يجب أن يكون أكبر من صفر');
                }
            }
            return; // Skip further validation for updates
        }

        if (!isset($params['id'])) {
            // Required fields validation for new records (user_expected_price is optional now)
            $required = ['user_id', 'brand', 'model', 'year', 'fuel_type', 'transmission'];
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

            // Price validation (only if provided)
            if (isset($params['user_expected_price']) && $params['user_expected_price'] !== null && $params['user_expected_price'] <= 0) {
                throw new \Exception('السعر المتوقع يجب أن يكون أكبر من صفر');
            }

            // Fair price validation (admin only)
            if (isset($params['fair_price']) && $params['fair_price'] !== null && $params['fair_price'] <= 0) {
                throw new \Exception('السعر العادل يجب أن يكون أكبر من صفر');
            }
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
    }

    public function afterCommit($model, $params)
    {
        // If fair_price was set (pricing operation), deactivate other user cars
        if (isset($params['fair_price']) && $params['fair_price'] !== null) {
            UserCar::where('user_id', $model->user_id)
                ->where('id', '!=', $model->id)
                ->update(['is_active' => false]);
        }
    }
}
