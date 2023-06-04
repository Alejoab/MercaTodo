<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface AcceptOrder
{
    public function execute(Order $order): void;
}
