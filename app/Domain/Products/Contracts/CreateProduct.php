<?php

namespace App\Domain\Products\Contracts;

use App\Domain\Products\Models\Product;

interface CreateProduct
{
    /**
     * Creates a new product.
     *
     * @param array $data
     *
     * @return \App\Domain\Products\Models\Product
     */
    public function execute(array $data): Product;
}
