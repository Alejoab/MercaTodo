<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface AcceptOrder
{
    /**
     * Changes the status of the order to accepted
     *
     * @param Order $order
     *
     * @return void
     */
    public function execute(Order $order): void;
}
