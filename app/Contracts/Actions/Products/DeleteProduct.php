<?php

namespace App\Contracts\Actions\Products;

interface DeleteProduct
{
    public function execute(int $id): void;
}
