<?php

namespace App\Services\Saving;

use App\Services\BaseSavingService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSavingService extends BaseSavingService
{
    public string $modelName = User::class;

    public function prepareArray($params)
    {
        // Set default values for new users
        if (!isset($params['id'])) {
            $params['name'] = $params['name'] ?? 'عميل_' . $params['phone'];
            $params['email'] = $params['email'] ?? 'user_' . $params['phone'] . '@temp.e7lal.com';
            $params['password'] = $params['password'] ?? Hash::make($params['phone']);
        }

        return $params;
    }

    public function validate($params)
    {
        // For new users (no ID provided)
        if (!isset($params['id'])) {
            $required = ['phone'];
            foreach ($required as $field) {
                if (!isset($params[$field]) || empty($params[$field])) {
                    throw new \Exception("الحقل {$field} مطلوب");
                }
            }

            // Check if phone already exists
            if (User::where('phone', $params['phone'])->exists()) {
                throw new \Exception('رقم الهاتف مسجل بالفعل');
            }

            // Phone validation
            if (!preg_match('/^[0-9+\-\s()]+$/', $params['phone'])) {
                throw new \Exception('رقم الهاتف غير صحيح');
            }

            // Email uniqueness check
            $email = $params['email'] ?? 'user_' . $params['phone'] . '@temp.e7lal.com';
            if (User::where('email', $email)->exists()) {
                throw new \Exception('البريد الإلكتروني مسجل بالفعل');
            }
        }
    }
}