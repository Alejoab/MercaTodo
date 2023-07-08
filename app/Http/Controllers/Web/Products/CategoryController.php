<?php

namespace App\Http\Controllers\Web\Products;

use App\Domain\Products\Services\CategoriesService;
use App\Http\Controllers\Web\Controller;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
    /**
     * List all categories.
     *
     * @param CategoriesService $categoriesService
     *
     * @return Collection
     */
    public function list(CategoriesService $categoriesService): Collection
    {
        return $categoriesService->list();
    }
}
