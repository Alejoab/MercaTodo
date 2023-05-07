<?php

namespace App\Contracts\Actions\Products;

use App\Models\Product;

interface UpdateProduct
{
    public function execute(Product $product, array $data): void;
}
