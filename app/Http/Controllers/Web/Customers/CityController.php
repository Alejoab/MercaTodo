<?php

namespace App\Http\Controllers\Web\Customers;

use App\Domain\Customers\Services\CitiesService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class CityController extends Controller
{
    public function citiesByDepartment(int $id, CitiesService $citiesService): Collection|array
    {
        return $citiesService->citiesByDepartment($id);
    }

    public function departments(CitiesService $citiesService): Collection|array
    {
        return $citiesService->departments();
    }
}
