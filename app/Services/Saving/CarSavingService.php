<?php

namespace App\Services\Saving;

use App\Services\BaseSavingService;
use App\Models\Car;

class CarSavingService extends BaseSavingService
{
    public string $modelName = Car::class;

    public function prepareArray($params)
    {
        // Set default status for new cars
        if (!isset($params['id'])) {
            $params['status'] = $params['status'] ?? 'available';
        }

        return $params;
    }

    public function validate($params)
    {
        // Required fields validation
        $required = ['brand', 'model', 'year', 'fuel_type', 'transmission', 'price'];
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
        if (isset($params['price']) && $params['price'] <= 0) {
            throw new \Exception('السعر يجب أن يكون أكبر من صفر');
        }
    }

    public function saveRelatedData($model, $params)
    {
        // Handle image upload if provided
        if (isset($params['image_file']) && $params['image_file']) {
            $file = $params['image_file'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cars'), $filename);
            $model->image = 'uploads/cars/' . $filename;
            $model->save();
        }
    }
}


