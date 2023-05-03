<?php

namespace App\Contracts\Actions\Products;

interface RestoreProduct
{
    public function execute(int $id): void;
}
