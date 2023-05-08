<?php

namespace App\Contracts\Actions\Products;

interface CreateProduct
{
    /**
     * Creates a new product.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function execute(array $data): mixed;
}
