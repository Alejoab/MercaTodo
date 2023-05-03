<?php

namespace App\Services\Products;

use App\Models\Brand;
use App\Services\Cache\CacheDeleteService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BrandsService
{
    public function store($name): Builder|Model
    {
        $name = ucwords(strtolower($name));

        $brand = Brand::query()->where('name', '=', $name)->first();

        if (!is_null($brand)) {
            return $brand;
        }

        (new CacheDeleteService())->deleteBrandsCache();

        return Brand::query()->create([
            'name' => $name,
        ]);
    }

    public function searchBrands($search): array|Collection
    {
        return Brand::query()
            ->where('name', 'like', "%".$search."%")
            ->get('name');
    }

    public function brandsByCategory(int|null $id): Collection|array
    {
        if (is_null($id)) {
            return Cache::rememberForever('brands_by_category_all', function () {
                return Brand::query()->get(['name', 'id']);
            });
        } else {
            $key = 'brands_by_category_'.$id;

            return Cache::rememberForever($key, function () use ($id) {
                return Brand::query()->whereHas('products', function ($query) use ($id) {
                    $query->withTrashed()->where('category_id', $id);
                })->get(['name', 'id']);
            });
        }
    }
}
