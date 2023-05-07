<?php

namespace App\Contracts\Actions\Products;

use App\Models\Product;

interface ForceDeleteProduct
{
    public function execute(Product $product): void;
}
