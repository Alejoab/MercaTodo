<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface UpdateOrder
{
    public function execute(Order $order, array $data): void;
}
