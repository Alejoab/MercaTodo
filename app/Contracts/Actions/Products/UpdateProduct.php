<?php

namespace App\Contracts\Actions\Products;

interface UpdateProduct
{
    public function execute(int $id, array $data): void;
}
