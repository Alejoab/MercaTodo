<?php

namespace App\Services\Products;

use App\Models\Category;
use App\Services\Cache\CacheDeleteService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CategoriesService
{
    public function list(): Collection
    {
        return Cache::rememberForever('categories', function () {
            return Category::all('id', 'name');
        });
    }

    public function store($name): Builder|Model
    {
        $name = ucwords(strtolower($name));

        $category = Category::query()->where('name', '=', $name)->first();

        if (!is_null($category)) {
            return $category;
        }

        (new CacheDeleteService())->deleteCategoriesCache();

        return Category::query()->create([
            'name' => $name,
        ]);
    }

    public function searchCategories($search): array|Collection
    {
        return Category::query()
            ->where('name', 'like', "%".$search."%")
            ->get('name');
    }
}
