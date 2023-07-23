<?php

namespace App\Domain\Carts\Contracts;

interface DeleteProductCart
{
    public function execute(int $userId, array $data): void;
}
