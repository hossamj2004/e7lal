<?php

namespace App\Services\Saving;

use App\Services\BaseSavingService;
use App\Models\Offer;
use App\Models\UserCar;
use App\Models\Car;

class OfferSavingService extends BaseSavingService
{
    public string $modelName = Offer::class;

    public function prepareArray($params)
    {
        // Set default status for new offers
        if (!isset($params['id'])) {
            $params['status'] = 'pending';
        }

        return $params;
    }

    public function validate($params)
    {
        // Required fields validation
        $required = ['user_id', 'user_car_id', 'car_id', 'offered_difference'];
        foreach ($required as $field) {
            if (!isset($params[$field])) {
                throw new \Exception("الحقل {$field} مطلوب");
            }
        }

        // Validate user car exists and belongs to user
        $userCar = UserCar::find($params['user_car_id']);
        if (!$userCar) {
            throw new \Exception('عربيتك غير موجودة');
        }
        if ($userCar->user_id != $params['user_id']) {
            throw new \Exception('هذه العربية ليست ملكك');
        }
        if ($userCar->status !== 'priced') {
            throw new \Exception('عربيتك لم يتم تقييمها بعد');
        }

        // Validate target car exists and is available
        $car = Car::find($params['car_id']);
        if (!$car) {
            throw new \Exception('العربية المطلوبة غير موجودة');
        }
        if ($car->status !== 'available') {
            throw new \Exception('العربية المطلوبة غير متاحة حالياً');
        }

        // Check for existing pending offer
        if (!isset($params['id'])) {
            $existingOffer = Offer::where('user_id', $params['user_id'])
                ->where('car_id', $params['car_id'])
                ->where('status', 'pending')
                ->first();
            if ($existingOffer) {
                throw new \Exception('لديك عرض قائم على هذه العربية بالفعل');
            }
        }
    }
}


