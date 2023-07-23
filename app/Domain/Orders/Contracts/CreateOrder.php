<?php

namespace App\Domain\Orders\Contracts;

use App\Domain\Orders\Models\Order;

interface CreateOrder
{
    public function execute(int $userId, array $cart, string $method): Order;
}
