<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface RejectOrder
{
    /**
     * Changes the status of the order. If the order is not active, returns the items to the stock
     *
     * @param Order $order
     *
     * @return void
     */
    public function execute(Order $order): void;
}
