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
        // Hash password if provided
        if (isset($params['password']) && !empty($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        } elseif (isset($params['id'])) {
            // If updating and password is empty, remove it from params
            unset($params['password']);
        }

        // Set default admin status
        if (!isset($params['id'])) {
            $params['is_admin'] = $params['is_admin'] ?? false;
        }

        return $params;
    }

    public function validate($params)
    {
        // Required fields for new users
        if (!isset($params['id'])) {
            $required = ['name', 'email', 'password'];
            foreach ($required as $field) {
                if (!isset($params[$field]) || empty($params[$field])) {
                    throw new \Exception("الحقل {$field} مطلوب");
                }
            }
        }

        // Email uniqueness check
        if (isset($params['email'])) {
            $query = User::where('email', $params['email']);
            if (isset($params['id'])) {
                $query->where('id', '!=', $params['id']);
            }
            if ($query->exists()) {
                throw new \Exception('البريد الإلكتروني مستخدم بالفعل');
            }
        }

        // Email format validation
        if (isset($params['email']) && !filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('البريد الإلكتروني غير صحيح');
        }
    }
}


