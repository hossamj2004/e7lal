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
        // Handle admin updates (only admin_notes and status changes)
        if (isset($params['id']) && (!isset($params['phone']) || !isset($params['car_model']))) {
            // This is an admin update - only handle admin_notes and status
            // Set responded_at when status changes from pending to in_progress
            if (isset($params['status']) && isset($params['id'])) {
                $existingRequest = ExchangeRequest::find($params['id']);
                if ($existingRequest && $existingRequest->status === 'pending' && $params['status'] === 'in_progress') {
                    $params['responded_at'] = now();
                }
            }
            return $params;
        }

        // Prepare related data arrays for saving (frontend form submissions)
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
        // Handle admin updates (only admin_notes and status changes)
        if (isset($params['id']) && (!isset($params['phone']) || !isset($params['car_model']))) {
            // Admin update validation
            if (isset($params['status'])) {
                $allowedStatuses = ['pending', 'in_progress', 'completed', 'cancelled'];
                if (!in_array($params['status'], $allowedStatuses)) {
                    throw new \Exception('الحالة غير صحيحة');
                }
            }

            if (isset($params['admin_notes']) && strlen($params['admin_notes']) > 1000) {
                throw new \Exception('الملاحظات يجب ألا تتجاوز 1000 حرف');
            }

            return; // Skip other validations for admin updates
        }

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
        // Skip related data saving for admin updates
        if (isset($params['id']) && (!isset($params['phone']) || !isset($params['car_model']))) {
            return; // Admin update - no related data to save
        }

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
