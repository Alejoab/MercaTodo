<?php

namespace App\Domain\Products\Contracts;

use App\Domain\Products\Models\Product;

interface UpdateProduct
{
    public function execute(Product $product, array $data): Product;
}
