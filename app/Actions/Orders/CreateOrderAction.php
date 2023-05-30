<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\CreateOrder;
use App\Enums\OrderStatus;
use App\Models\Order;

class CreateOrderAction implements CreateOrder
{

    public function execute(int $userId, array $cart): Order
    {
        $action = new CreateOrderDetailAction();

        /**
         * @var Order $order
         */
        $order = Order::query()->create([
            'code' => 'ORD-'.time(),
            'user_id' => $userId,
            'status' => OrderStatus::IN_PROGRESS,
        ]);

        $total = 0;
        foreach ($cart as $product_id => $quantity) {
            $orderDetail = $action->execute([
                'order_id' => $order->getKey(),
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);
            $total += $orderDetail->subtotal;
        }

        $order->total = $total;
        $order->save();

        return $order;
    }
}
