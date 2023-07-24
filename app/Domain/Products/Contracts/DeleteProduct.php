<?php

namespace App\Domain\Products\Contracts;

use App\Domain\Products\Models\Product;

interface DeleteProduct
{
    public function execute(Product $product): void;
}
