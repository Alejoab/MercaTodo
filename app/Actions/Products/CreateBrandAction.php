<?php

namespace App\Actions\Products;

use App\Models\Brand;
use App\Services\Cache\CacheDeleteService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreateBrandAction
{
    public function execute($name): Builder|Model
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
}
