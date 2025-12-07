<?php

namespace App\Filters;

class OfferFilter extends BaseFilter
{
    public function status($value): void
    {
        $this->query->where('offers.status', $value);
    }

    public function user_id($value): void
    {
        $this->query->where('offers.user_id', $value);
    }

    public function car_id($value): void
    {
        $this->query->where('offers.car_id', $value);
    }

    public function prepareFiltersArray($filters)
    {
        return $filters;
    }

    public function allowedSearchFields(): array
    {
        return [
            'id', 'user_id', 'user_car_id', 'car_id', 'offered_difference',
            'status', 'created_at'
        ];
    }

    public function getWithGroups(): array
    {
        return [
            'basic' => ['user', 'car'],
            'detailed' => ['user', 'car', 'userCar'],
        ];
    }
}


