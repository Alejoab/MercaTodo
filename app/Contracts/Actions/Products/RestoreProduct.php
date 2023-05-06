<?php

namespace App\Contracts\Actions\Products;

use App\Models\Product;

interface RestoreProduct
{
    public function execute(Product $product): void;
}
