<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\CreateProduct;
use App\Models\Product;
use App\Services\BrandsService;
use App\Services\CategoriesService;
use App\Services\ProductImagesService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreateProductAction implements CreateProduct
{
    public function execute(array $data): Builder|Model
    {
        $brandService = new BrandsService();
        $categoryService = new CategoriesService();
        $imageService = new ProductImagesService();

        $brand = $brandService->store($data['brand_name']);
        $category = $categoryService->store($data['category_name']);

        $data['image'] = $imageService->storeImage($data['image']);

        return Product::query()->create([
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'category_id' => $category->getAttribute('id'),
            'brand_id' => $brand->getAttribute('id'),
            'image' => $data['image'],
        ]);
    }
}
