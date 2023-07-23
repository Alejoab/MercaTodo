<?php

namespace App\Domain\Products\Contracts;

use App\Domain\Products\Models\Product;

interface ForceDeleteProduct
{
    public function execute(Product $product): void;
}
