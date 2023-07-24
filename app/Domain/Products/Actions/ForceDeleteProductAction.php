<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Contracts\ForceDeleteProduct;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Services\ProductImagesService;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use Illuminate\Support\Facades\Log;

class ForceDeleteProductAction implements ForceDeleteProduct
{

    /**
     * @throws CustomException
     */
    public function execute(Product $product): void
    {
        $imageService = new ProductImagesService();

        if ($product->getAttribute('image') !== null) {
            $imageService->deleteImage($product->getAttribute('image'));
        }

        $product->forceDelete();

        Log::warning('[FORCE DELETE]', [
            'admin_id' => auth()->user()->getAuthIdentifier(),
            'product_id' => $product->getKey(),
            'product_code' => $product->getAttribute('code'),
        ]);
    }
}
