<?php

namespace App\Services\Payment\PlaceToPay;

use App\Models\Order;
use App\Models\Order_detail;

class OrderToPayment
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Returns the payment array for the placetopay request
     *
     * @return array
     */
    public function getPayment(): array
    {
        return [
            'reference' => $this->order->code,
            'description' => date($this->order->created_at),
            'amount' => $this->getAmount(),
            'items' => $this->getItems(),
        ];
    }

    /**
     * Returns the amount and currency
     *
     * @return array
     */
    private function getAmount(): array
    {
        return [
            'currency' => 'USD',
            'total' => $this->order->total,
        ];
    }

    /**
     * Returns the items array
     *
     * @return array
     */
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
