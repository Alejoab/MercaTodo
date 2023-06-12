<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface UpdateOrder
{
    /**
     * Updates the order
     *
     * @param Order $order
     * @param array $data
     *
     * @return void
     */
    public function execute(Order $order, array $data): void;
}
