<?php

namespace App\Services\Products;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class BrandsService
{
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
