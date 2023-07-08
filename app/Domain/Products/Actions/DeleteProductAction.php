<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Contracts\DeleteProduct;
use App\Domain\Products\Models\Product;

class DeleteProductAction implements DeleteProduct
{

    public function execute(Product $product): void
    {
        $product->delete();
    }
}
