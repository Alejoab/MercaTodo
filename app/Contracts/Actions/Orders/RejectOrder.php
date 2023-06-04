<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface RejectOrder
{
    public function execute(Order $order): void;
}
