<?php

namespace App\Contracts\Actions\Products;

use App\Models\Product;

interface DeleteProduct
{
    public function execute(Product $product): void;
}
