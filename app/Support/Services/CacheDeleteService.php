<?php

namespace App\Support\Services;

use App\Domain\Products\Models\Category;
use Illuminate\Support\Facades\Cache;

class CacheDeleteService
{
    /**
     * Deletes all categories from cache.
     *
     * @return void
     */
    public function deleteCategoriesCache(): void
    {
        Cache::forget('categories');
    }

    /**
     * Deletes all brands from cache
     *
     * @return void
     */
    public function deleteBrandsCache(): void
    {
        Cache::forget('brands_by_category_all');

        for ($i = 1; $i <= Category::query()->count(); $i++) {
            Cache::forget('brands_by_category_'.$i);
        }
    }
}
