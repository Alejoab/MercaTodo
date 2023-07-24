<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Models\Brand;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use App\Support\Services\CacheDeleteService;
use Throwable;

class CreateBrandAction
{
    /**
     * @throws CustomException
     */
    public function execute($name): Brand
    {
        try {
            $name = ucwords(strtolower($name));

            /**
             * @var ?Brand $brand
             */
            $brand = Brand::query()->where('name', '=', $name)->first();

            if (!$brand) {
                (new CacheDeleteService())->deleteBrandsCache();

                /**
                 * @var Brand $brand
                 */
                $brand = Brand::query()->create([
                    'name' => $name,
                ]);
            }

            return $brand;
        } catch (Throwable $e) {
            throw new ApplicationException($e, [
                'name' => $name,
            ]);
        }
    }
}
