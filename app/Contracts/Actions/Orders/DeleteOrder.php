<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface DeleteOrder
{
    public function execute(Order $order): void;
}
