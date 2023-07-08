<?php

namespace App\Http\Controllers;

use App\Domain\Products\Services\BrandsService;
use Illuminate\Database\Eloquent\Collection;

class BrandController extends Controller
{
    public function brandsByCategory(BrandsService $brandsService, int $id = null): Collection|array
    {
        return $brandsService->brandsByCategory($id);
    }
}
