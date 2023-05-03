<?php

namespace App\Contracts\Actions\Products;

interface CreateProduct
{
    public function execute(array $data): mixed;
}
