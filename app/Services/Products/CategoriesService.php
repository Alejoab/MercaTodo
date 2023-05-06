<?php

namespace App\Services\Products;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoriesService
{
    public function list(): Collection
    {
        return Cache::rememberForever('categories', function () {
            return Category::all('id', 'name');
        });
    }

    public function searchCategories($search): array|Collection
    {
        return Category::query()
            ->where('name', 'like', "%".$search."%")
            ->get('name');
    }
}
