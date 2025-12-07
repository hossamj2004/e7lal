<?php

namespace App\Filters;

class CarFilter extends BaseFilter
{
    public function status($value): void
    {
        $this->query->where('cars.status', $value);
    }

    public function brand($value): void
    {
        $this->query->where('cars.brand', $value);
    }

    public function year($value): void
    {
        $this->query->where('cars.year', $value);
    }

    public function fuel_type($value): void
    {
        $this->query->where('cars.fuel_type', $value);
    }

    public function price_min($value): void
    {
        $this->query->where('cars.price', '>=', $value);
    }

    public function price_max($value): void
    {
        $this->query->where('cars.price', '<=', $value);
    }

    public function prepareFiltersArray($filters)
    {
        // Default to available cars
        $filters['status'] ??= 'available';
        return $filters;
    }

    public function allowedSearchFields(): array
    {
        return [
            'id', 'brand', 'model', 'year', 'color', 'mileage',
            'fuel_type', 'transmission', 'price', 'status', 'created_at'
        ];
    }
}


