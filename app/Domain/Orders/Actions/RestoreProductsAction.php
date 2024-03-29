<?php

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Contracts\RestoreProducts;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\Order_detail;

class RestoreProductsAction implements RestoreProducts
{

    public function execute(Order $order): void
    {
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
