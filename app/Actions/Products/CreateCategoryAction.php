<?php

namespace App\Actions\Products;

use App\Exceptions\ApplicationException;
use App\Models\Category;
use App\Services\Cache\CacheDeleteService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CreateCategoryAction
{
    /**
     * @throws ApplicationException
     */
    public function execute($name): Builder|Model
    {
        try {
            $name = ucwords(strtolower($name));
            $category = Category::query()->where('name', '=', $name)->first();
            if (!is_null($category)) {
                return $category;
            }
            (new CacheDeleteService())->deleteCategoriesCache();

            return Category::query()->create([
                'name' => $name,
            ]);
        } catch (Throwable $e) {
            throw new ApplicationException($e);
        }
    }
}
