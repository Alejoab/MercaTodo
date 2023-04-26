<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoriesService
{
    public function list(): Collection
    {
        return Category::all('id', 'name');
    }

    public function store($name): Builder|Model
    {
        return Category::query()->firstOrCreate(['name' => $name], ['name' => $name]);
    }

    public function searchCategories($search): array|Collection
    {
        return Category::query()
            ->where('name', 'like', "%".$search."%")
            ->get('name');
    }
}
