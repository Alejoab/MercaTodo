<?php

namespace App\Domain\Carts\Contracts;

interface DeleteProductCart
{
    /**
     * Deletes a product from the cart
     *
     * @param int   $userId
     * @param array $data
     *
     * @return void
     */
    public function execute(int $userId, array $data): void;
}
