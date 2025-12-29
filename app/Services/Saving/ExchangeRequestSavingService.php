<?php

namespace App\Services\Saving;

use App\Services\BaseSavingService;
use App\Models\ExchangeRequest;
use App\Models\User;
use App\Services\Saving\UserSavingService;
use App\Services\Saving\UserCarSavingService;

class ExchangeRequestSavingService extends BaseSavingService
{
    public string $modelName = ExchangeRequest::class;

    public function prepareArray($params)
    {
        // Prepare related data arrays for saving
        $user = User::where('phone', $params['phone'])->first();

        if (!$user) {
            // Prepare user data for creation
            $params['User'] = [
                'phone' => $params['phone'],
            ];
        }

        // Prepare user car data
        $params['UserCar'] = [
            'ignore_validations'=>1,
            'is_active'=>1,
            'brand' => explode(' ', $params['car_model'])[0] ?? $params['car_model'],
            'model' => $params['car_model'],
            'year' => date('Y'),
            'location' => $params['location'],
            'description' => 'تم الإرسال من خلال نموذج طلب التبديل',
        ];

        return $params;
    }

    public function validate($params)
    {
        // Required fields validation for new records
        $required = ['car_model', 'location', 'phone'];
        foreach ($required as $field) {
            if (!isset($params[$field]) || empty($params[$field])) {
                throw new \Exception("الحقل {$field} مطلوب");
            }
        }

        // Phone validation
        if (isset($params['phone'])) {
            if (!preg_match('/^[0-9+\-\s()]+$/', $params['phone'])) {
                throw new \Exception('رقم الهاتف غير صحيح');
            }
        }

        // Check if phone already exists and has a user (only for updates)
        if (isset($params['id'])) {
            $exchangeRequest = ExchangeRequest::find($params['id']);
            if ($exchangeRequest && User::where('phone', $params['phone'])->where('id', '!=', $exchangeRequest->user_id)->exists()) {
                throw new \Exception('رقم الهاتف مسجل لمستخدم آخر');
            }
        }
    }

    public function saveRelatedData($model, $params)
    {
        $userId = null;

        // Save user if prepared
        if (isset($params['User'])) {
            $user = app(UserSavingService::class)->saveOne($params['User']);
            $userId = $user->id;

            // Update the model with the correct user_id
            $model->user_id = $userId;
            $model->save();
        } else {
            // Use existing user from phone lookup
            $existingUser = User::where('phone', $params['phone'])->first();
            $userId = $existingUser ? $existingUser->id : $model->user_id;
        }

        // Save user car if prepared
        if (isset($params['UserCar'])) {
            $params['UserCar']['user_id'] = $userId;
            app(UserCarSavingService::class)->saveOne($params['UserCar']);
        }
    }

    public function afterCommit($result, $params)
    {
        // Log the user in after successful save
        if(isset($result->user))
        \Illuminate\Support\Facades\Auth::login($result->user);
    }
}
