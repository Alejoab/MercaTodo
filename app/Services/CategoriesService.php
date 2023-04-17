<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoriesService
{
    public function store($name): Category
    {
        return Category::firstOrCreate(['name' => $name], ['name' => $name]);
    }

    public function searchCategories($search): array|Collection
    {
        return Category::query()
            ->where('name', 'like', "%" . $search . "%")
            ->get('name');
    }
}
