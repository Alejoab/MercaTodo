<?php

namespace App\Http\Controllers;

use App\Domain\Customers\Services\CitiesService;
use Illuminate\Database\Eloquent\Collection;

class CityController extends Controller
{
    /**
     * List cities by department.
     *
     * @param int                                          $id
     * @param \App\Domain\Customers\Services\CitiesService $citiesService
     *
     * @return Collection|array
     */
    public function citiesByDepartment(int $id, CitiesService $citiesService): Collection|array
    {
        return $citiesService->citiesByDepartment($id);
    }

    /**
     * Lists departments.
     *
     * @param CitiesService $citiesService
     *
     * @return Collection|array
     */
    public function departments(CitiesService $citiesService): Collection|array
    {
        return $citiesService->departments();
    }
}
