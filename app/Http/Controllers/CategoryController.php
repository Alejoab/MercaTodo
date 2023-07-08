<?php

namespace App\Http\Controllers;

use App\Domain\Products\Services\CategoriesService;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
    /**
     * List all categories.
     *
     * @param \App\Domain\Products\Services\CategoriesService $categoriesService
     *
     * @return Collection
     */
    public function list(CategoriesService $categoriesService): Collection
    {
        return $categoriesService->list();
    }
}
