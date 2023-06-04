<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\UpdateOrder;
use App\Models\Order;

class UpdateOrderAction implements UpdateOrder
{

    public function execute(Order $order, array $data): void
    {
        $order->fill($data);
        $order->save();
    }
}
