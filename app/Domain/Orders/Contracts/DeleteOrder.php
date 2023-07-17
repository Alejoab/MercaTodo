<?php

namespace App\Domain\Orders\Contracts;

use App\Domain\Orders\Models\Order;

interface DeleteOrder
{
    /**
     * Deletes an order and returns the items to the stock
     *
     * @param Order $order
     *
     * @return void
     */
    public function execute(Order $order): void;
}
