<?php

namespace App\Contracts\Actions\Products;

interface ForceDeleteProduct
{
    public function execute(int $id): void;
}
