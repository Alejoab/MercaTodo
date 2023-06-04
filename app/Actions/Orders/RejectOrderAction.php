<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\RejectOrder;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Order_detail;

class RejectOrderAction implements RejectOrder
{

    public function execute(Order $order): void
    {
        $order->status = OrderStatus::REJECTED;
        $order->save();

        foreach ($order->order_detail as $order_detail) {
            /**
             * @var Order_detail $order_detail
             */
            if (!$order_detail->product) {
                continue;
            }

            if ($order_detail->product->deleted_at !== null) {
                $order_detail->product->restore();
            }

            $order_detail->product->stock += $order_detail->quantity;
            $order_detail->product->save();
        }
    }
}
