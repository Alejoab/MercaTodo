<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

//use Intervention\Image\Facades\Image;

class ProductsService
{
    public function store(array $data)
    {
        $category = Category::query()->where('name', $data['category_name'])->first() ?: Category::create(['name' => $data['category_name']]);
        $brand = Brand::query()->where('name', $data['brand_name'])->first() ?: Brand::create(['name' => $data['brand_name']]);

        $file_name = null;
        if ($data['image'] !== null) {
            $file_name = time() . '.' . $data['image']->extension();
            $data['image']->move(storage_path('app\public\product_images'), $file_name);
        }

        return Product::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'image' => $file_name,
        ]);
    }

    public function searchCategories($search): array|Collection
    {
        return Category::query()
            ->where('name', 'like', "%" . $search . "%")
            ->get('name');
    }

    public function searchBrands($search): array|Collection
    {
        return Brand::query()
            ->where('name', 'like', "%" . $search . "%")
            ->get('name');
    }
}
