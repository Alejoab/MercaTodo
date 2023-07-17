<?php

namespace App\Domain\Products\Contracts;

use App\Domain\Products\Models\Product;

interface RestoreProduct
{
    /**
     * Restores a product.
     *
     * @param Product $product
     *
     * @return void
     */
    public function execute(Product $product): void;
}
