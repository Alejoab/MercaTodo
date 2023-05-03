<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BrandsService
{
    public function store($name): Builder|Model
    {
        $name = ucwords(strtolower($name));

        return Brand::query()->firstOrCreate(['name' => $name], ['name' => $name]);
    }

    public function searchBrands($search): array|Collection
    {
        return Brand::query()
            ->where('name', 'like', "%".$search."%")
            ->get('name');
    }

    public function brandsByCategory(int|null $id): Collection|array
    {
        return Brand::query()->whereHas('products', function ($query) use ($id) {
            $query->withTrashed()->when($id, function ($query, $id) {
                $query->where('category_id', $id);
            });
        })->get(['name', 'id']);
    }
}
