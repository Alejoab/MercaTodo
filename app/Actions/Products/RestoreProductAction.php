<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\RestoreProduct;
use App\Models\Product;

class RestoreProductAction implements RestoreProduct
{

    public function execute(Product $product): void
    {
        $product->restore();
    }
}
