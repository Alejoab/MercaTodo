<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Models\Category;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use App\Support\Services\CacheDeleteService;
use Throwable;

class CreateCategoryAction
{
    /**
     * @throws CustomException
     */
    public function execute($name): Category
    {
        try {
            $name = ucwords(strtolower($name));

            /**
             * @var ?Category $category
             */
            $category = Category::query()->where('name', '=', $name)->first();

            if (!$category) {
                (new CacheDeleteService())->deleteCategoriesCache();

                /**
                 * @var Category $category
                 */
                $category = Category::query()->create([
                    'name' => $name,
                ]);
            }

            return $category;
        } catch (Throwable $e) {
            throw new ApplicationException($e, [
                'name' => $name,
            ]);
        }
    }
}
