<?php

namespace App\Http\Controllers;

use App\Services\CategoriesService;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
    public function list(CategoriesService $service): Collection
    {
        return $service->list();
    }
}
