<?php

namespace App\Actions\Products;

use App\Exceptions\ApplicationException;
use App\Models\Brand;
use App\Services\Cache\CacheDeleteService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CreateBrandAction
{
    /**
     * @throws ApplicationException
     */
    public function execute($name): Builder|Model
    {
        try {
            $name = ucwords(strtolower($name));
            $brand = Brand::query()->where('name', '=', $name)->first();
            if (!is_null($brand)) {
                return $brand;
            }
            (new CacheDeleteService())->deleteBrandsCache();

            return Brand::query()->create([
                'name' => $name,
            ]);
        } catch (Throwable $e) {
            throw new ApplicationException($e);
        }
    }
}
