<?php

namespace App\Domain\Products\Contracts;

use App\Domain\Products\Models\Product;

interface CreateProduct
{
    public function execute(array $data): Product;
}
