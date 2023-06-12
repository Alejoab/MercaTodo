<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order_detail;

interface CreateOrderDetail
{
    /**
     * Creates the order detail of an order
     *
     * @param array $data
     *
     * @return Order_detail
     */
    public function execute(array $data): Order_detail;
}
