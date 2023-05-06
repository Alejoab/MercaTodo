<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\ForceDeleteProduct;
use App\Models\Product;
use App\Services\Products\ProductImagesService;

class ForceDeleteProductAction implements ForceDeleteProduct
{

    public function execute(Product $product): void
    {
        $imageService = new ProductImagesService();

        if ($product->getAttribute('image') !== null) {
            $imageService->deleteImage($product->getAttribute('image'));
        }

        $product->forceDelete();
    }
}
