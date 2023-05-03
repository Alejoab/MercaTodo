<?php

namespace App\Http\Controllers;

use App\Services\CitiesService;
use Illuminate\Database\Eloquent\Collection;

class CityController extends Controller
{
    public function citiesByDepartment(int $id, CitiesService $service): Collection|array
    {
        return $service->citiesByDepartment($id);
    }

    public function departments(CitiesService $service): Collection|array
    {
        return $service->departments();
    }
}
