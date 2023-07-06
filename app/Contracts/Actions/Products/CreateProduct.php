<?php

namespace App\Contracts\Actions\Products;

use App\Models\Product;

interface CreateProduct
{
    /**
     * Creates a new product.
     *
     * @param array $data
     *
     * @return Product
     */
    public function execute(array $data): Product;
}
