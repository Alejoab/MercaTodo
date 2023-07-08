<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Models\Brand;
use App\Support\Exceptions\ApplicationException;
use App\Support\Services\CacheDeleteService;
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
            throw new ApplicationException($e, [
                'name' => $name,
            ]);
        }
    }
}
