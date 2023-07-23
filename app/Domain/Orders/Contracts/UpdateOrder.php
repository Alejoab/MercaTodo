<?php

namespace App\Domain\Orders\Contracts;

use App\Domain\Orders\Models\Order;

interface UpdateOrder
{
    public function execute(Order $order, array $data): void;
}
