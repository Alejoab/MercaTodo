<?php

namespace App\Domain\Orders\Contracts;

use App\Domain\Orders\Models\Order;

interface AcceptOrder
{
    public function execute(Order $order): void;
}
