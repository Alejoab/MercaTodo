<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

class BrandsService
{
    public function store($name): Brand
    {
        return Brand::firstOrCreate(['name' => $name], ['name' => $name]);
    }

    public function searchBrands($search): array|Collection
    {
        return Brand::query()
            ->where('name', 'like', "%" . $search . "%")
            ->get('name');
    }
}
