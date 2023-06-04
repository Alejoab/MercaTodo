<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\AcceptOrder;
use App\Enums\OrderStatus;
use App\Models\Order;

class AcceptOrderAction implements AcceptOrder
{

    public function execute(Order $order): void
    {
        $order->status = OrderStatus::ACCEPTED;
        $order->save();
    }
}
