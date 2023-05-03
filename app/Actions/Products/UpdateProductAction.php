<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\UpdateProduct;
use App\Models\Product;
use App\Services\Products\BrandsService;
use App\Services\Products\CategoriesService;
use App\Services\Products\ProductImagesService;

class UpdateProductAction implements UpdateProduct
{

    public function execute(int $id, array $data): void
    {
        $product = Product::query()->findOrFail($id);

        $brandService = new BrandsService();
        $categoryService = new CategoriesService();
        $imageService = new ProductImagesService();

        $brand = $brandService->store($data['brand_name']);
        $category = $categoryService->store($data['category_name']);
        $data['brand_id'] = $brand->getAttribute('id');
        $data['category_id'] = $category->getAttribute('id');

        if ($data['image'] !== null) {
            $imageService->deleteImage($product->getAttribute('image'));
            $data['image'] = $imageService->storeImage($data['image']);
        } else {
            unset($data['image']);
        }

        $product->fill($data);
        $product->save();
    }
}
