<?php

namespace App\Contracts\Actions\Products;

use App\Models\Product;

interface ForceDeleteProduct
{
    /**
     * Force deletes a product.
     *
     * @param Product $product
     *
     * @return void
     */
    public function execute(Product $product): void;
}
