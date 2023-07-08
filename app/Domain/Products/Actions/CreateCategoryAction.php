<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Models\Category;
use App\Support\Exceptions\ApplicationException;
use App\Support\Services\CacheDeleteService;
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
            throw new ApplicationException($e, [
                'name' => $name,
            ]);
        }
    }
}
