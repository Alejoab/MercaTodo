<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface CreateOrder
{
    public function execute(int $userId, array $cart, string $method): Order;
}
