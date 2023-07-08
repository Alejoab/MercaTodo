<?php

namespace App\Domain\Orders\Contracts;

use App\Domain\Orders\Models\Order;

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
