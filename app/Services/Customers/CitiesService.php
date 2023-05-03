<?php

namespace App\Services\Customers;

use App\Models\City;
use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class CitiesService
{
    public function citiesByDepartment(int $department_id): Collection|array
    {
        return City::query()->where('department_id', $department_id)->get();
    }

    public function departments(): Collection
    {
        return Department::all();
    }
}
