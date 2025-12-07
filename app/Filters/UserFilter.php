<?php

namespace App\Filters;

class UserFilter extends BaseFilter
{
    public function is_admin($value): void
    {
        $this->query->where('users.is_admin', $value);
    }

    public function prepareFiltersArray($filters)
    {
        return $filters;
    }

    public function allowedSearchFields(): array
    {
        return [
            'id', 'name', 'email', 'is_admin', 'phone', 'created_at'
        ];
    }

    public function getWithGroups(): array
    {
        return [
            'basic' => ['userCars'],
            'detailed' => ['userCars', 'offers'],
        ];
    }
}


