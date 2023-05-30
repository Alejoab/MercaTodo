<?php

namespace App\Contracts\Actions\Carts;

interface AddProductCart
{
    public function execute(int $userId, array $data): void;
}
