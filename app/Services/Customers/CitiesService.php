<?php

namespace App\Services\Customers;

use App\Models\City;
use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CitiesService
{
    /**
     * Lists all the cities by department.
     *
     * @param int $department_id
     *
     * @return Collection|array
     */
    public function citiesByDepartment(int $department_id): Collection|array
    {
        return Cache::rememberForever('cities_by_department_'.$department_id, function () use ($department_id) {
            return City::query()->where('department_id', $department_id)->get();
        });
    }

    /**
     * Lists all departments.
     *
     * @return Collection
     */
    public function departments(): Collection
    {
        return Cache::rememberForever('departments', function () {
            return Department::all();
        });
    }
}
