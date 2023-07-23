<?php

namespace App\Domain\Products\Contracts;

use App\Domain\Products\Models\Product;

interface RestoreProduct
{
    public function execute(Product $product): void;
}
