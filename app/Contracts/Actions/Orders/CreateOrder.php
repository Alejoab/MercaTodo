<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface CreateOrder
{
    /**
     * Creates a new order and the order details
     *
     * @param int    $userId
     * @param array  $cart
     * @param string $method
     *
     * @return Order
     */
    public function execute(int $userId, array $cart, string $method): Order;
}
