<?php

namespace App\Domain\Payments\PlaceToPay;

use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\Order_detail;

class OrderToPayment
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getPayment(): array
    {
        return [
            'reference' => $this->order->code,
            'description' => date($this->order->created_at),
            'amount' => $this->getAmount(),
            'items' => $this->getItems(),
        ];
    }

    private function getAmount(): array
    {
        return [
            'currency' => 'USD',
            'total' => $this->order->total,
        ];
    }

    private function getItems(): array
    {
        $result = [];

        /**
         * @var Order_detail $order_detail
         */
        foreach ($this->order->order_detail as $order_detail) {
            $result[] = [
                'sku' => $order_detail->product->code,
                'name' => $order_detail->product->name,
                'qty' => $order_detail->quantity,
                'price' => $order_detail->amount,
            ];
        }

        return $result;
    }
}
