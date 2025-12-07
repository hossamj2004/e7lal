<?php

namespace App\Filters;

class UserCarFilter extends BaseFilter
{
    public function status($value): void
    {
        $this->query->where('user_cars.status', $value);
    }

    public function user_id($value): void
    {
        $this->query->where('user_cars.user_id', $value);
    }

    public function is_active($value): void
    {
        $this->query->where('user_cars.is_active', $value);
    }

    public function needs_pricing($value): void
    {
        if ($value) {
            $this->query->where('user_cars.status', 'pending');
        }
    }

    public function prepareFiltersArray($filters)
    {
        return $filters;
    }

    public function allowedSearchFields(): array
    {
        return [
            'id', 'user_id', 'brand', 'model', 'year', 'color', 'mileage',
            'fuel_type', 'transmission', 'user_expected_price', 'fair_price',
            'status', 'is_active', 'created_at'
        ];
    }

    public function getWithGroups(): array
    {
        return [
            'basic' => ['user'],
            'detailed' => ['user', 'offers'],
        ];
    }
}


