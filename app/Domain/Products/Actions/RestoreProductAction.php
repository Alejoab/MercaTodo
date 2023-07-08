<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Contracts\RestoreProduct;
use App\Domain\Products\Models\Product;

class RestoreProductAction implements RestoreProduct
{

    public function execute(Product $product): void
    {
        $product->restore();
    }
}
