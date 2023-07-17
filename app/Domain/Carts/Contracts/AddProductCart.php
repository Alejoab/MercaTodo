<?php

namespace App\Domain\Carts\Contracts;

interface AddProductCart
{
    /**
     * Adds a product to the cart
     *
     * @param int   $userId
     * @param array $data
     *
     * @return void
     */
    public function execute(int $userId, array $data): void;
}
