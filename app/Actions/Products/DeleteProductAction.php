<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\DeleteProduct;
use App\Models\Product;

class DeleteProductAction implements DeleteProduct
{

    public function execute(Product $product): void
    {
        $product->delete();
    }
}
