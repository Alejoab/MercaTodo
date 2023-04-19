<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

class CitiesService
{
    public function citiesByDepartment(int $department_id): Collection|array
    {
        return City::query()->where('department_id', $department_id)->get();
    }
}
