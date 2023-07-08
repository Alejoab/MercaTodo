<?php

namespace App\Domain\Orders\Contracts;

use App\Domain\Orders\Models\Order;

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
