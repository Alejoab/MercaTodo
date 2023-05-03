<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\RestoreProduct;
use App\Models\Product;

class RestoreProductAction implements RestoreProduct
{

    public function execute(int $id): void
    {
        // TODO: Call undefined method restore
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
    }
}
