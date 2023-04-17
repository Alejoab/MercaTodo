<?php

namespace App\Services;

use App\Models\Product;

//use Intervention\Image\Facades\Image;

class ProductsService
{
    public function store(array $data): Product
    {
        $brandService = new BrandsService();
        $categoryService = new CategoriesService();

        $brand = $brandService->store($data['brand_name']);
        $category = $categoryService->store($data['category_name']);

        $file_name = $data['image'] !== null ? $this->storeImage($data['image']) : null;

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

    public function storeImage($image): string
    {
        $file_name = time() . '.' . $image->extension();
        $image->move(storage_path('app\public\product_images'), $file_name);
        return $file_name;
    }
}
