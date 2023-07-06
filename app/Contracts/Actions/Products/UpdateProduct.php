<?php

namespace App\Contracts\Actions\Products;

use App\Models\Product;

interface UpdateProduct
{
    /**
     * Updates a product.
     *
     * @param Product $product
     * @param array   $data
     *
     * @return Product
     */
    public function execute(Product $product, array $data): Product;
}
