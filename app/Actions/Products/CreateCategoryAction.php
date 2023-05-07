<?php

namespace App\Actions\Products;

use App\Models\Category;
use App\Services\Cache\CacheDeleteService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreateCategoryAction
{
    public function execute($name): Builder|Model
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
}
