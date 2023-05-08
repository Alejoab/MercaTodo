<?php

namespace App\Contracts\Actions\Products;

use App\Models\Product;

interface DeleteProduct
{
    /**
     * Deletes a product.
     *
     * @param Product $product
     *
     * @return void
     */
    public function execute(Product $product): void;
}
