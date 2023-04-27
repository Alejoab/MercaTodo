<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\DeleteProduct;
use App\Models\Product;

class DeleteProductAction implements DeleteProduct
{

    public function execute(int $id): void
    {
        $product = Product::query()->findOrFail($id);
        $product->delete();
    }
}
