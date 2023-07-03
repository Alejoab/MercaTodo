<?php

namespace App\Http\Controllers;

use App\Services\Products\CategoriesService;
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
