<?php

namespace App\Domain\Carts\Contracts;

interface AddProductCart
{
    public function execute(int $userId, array $data): void;
}
