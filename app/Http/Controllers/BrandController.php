<?php

namespace App\Http\Controllers;

use App\Services\BrandsService;
use Illuminate\Database\Eloquent\Collection;

class BrandController extends Controller
{
    public function brandsByCategory(BrandsService $service, int $id = null): Collection|array
    {
        return $service->brandsByCategory($id);
    }
}
