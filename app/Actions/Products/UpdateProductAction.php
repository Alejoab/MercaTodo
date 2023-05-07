<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\UpdateProduct;
use App\Models\Product;
use App\Services\Products\ProductImagesService;

class UpdateProductAction implements UpdateProduct
{

    public function execute(Product $product, array $data): void
    {
        $brandAction = new CreateBrandAction();
        $categoryAction = new CreateCategoryAction();
        $imageService = new ProductImagesService();

        $brand = $brandAction->execute($data['brand_name']);
        $category = $categoryAction->execute($data['category_name']);
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
