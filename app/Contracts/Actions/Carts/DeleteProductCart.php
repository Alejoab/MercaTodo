<?php

namespace App\Contracts\Actions\Carts;

interface DeleteProductCart
{
    public function execute(int $userId, array $data): void;
}
