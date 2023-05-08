<?php

namespace App\Http\Controllers;

use App\Services\Customers\CitiesService;
use Illuminate\Database\Eloquent\Collection;

class CityController extends Controller
{
    /**
     * List cities by department.
     *
     * @param int           $id
     * @param CitiesService $service
     *
     * @return Collection|array
     */
    public function citiesByDepartment(int $id, CitiesService $service): Collection|array
    {
        return $service->citiesByDepartment($id);
    }

    /**
     * Lists departments.
     *
     * @param CitiesService $service
     *
     * @return Collection|array
     */
    public function departments(CitiesService $service): Collection|array
    {
        return $service->departments();
    }
}
