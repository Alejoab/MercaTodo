<?php

namespace App\Services\Cache;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CacheDeleteService
{
    public function deleteCategoriesCache(): void
    {
        Cache::forget('categories');
    }

    public function deleteBrandsCache(): void
    {
        Cache::forget('brands_by_category_all');

        for ($i = 1; $i <= Category::query()->count(); $i++) {
            Cache::forget('brands_by_category_'.$i);
        }
    }
}
