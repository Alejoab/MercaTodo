<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\ForceDeleteProduct;
use App\Models\Product;
use App\Services\Products\ProductImagesService;

class ForceDeleteProductAction implements ForceDeleteProduct
{

    public function execute(int $id): void
    {
        $product = Product::withTrashed()->findOrFail($id);

        $imageService = new ProductImagesService();

        if ($product->getAttribute('image') !== null) {
            $imageService->deleteImage($product->getAttribute('image'));
        }

        $product->forceDelete();
    }
}
