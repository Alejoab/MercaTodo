<?php

namespace App\Http\Controllers\Web\Products;

use App\Domain\Products\Services\CategoriesService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
    public function list(CategoriesService $categoriesService): Collection
    {
        return $categoriesService->list();
    }
}
