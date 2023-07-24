<?php

namespace App\Domain\Orders\Contracts;

use App\Domain\Orders\Models\Order;

interface DeleteOrder
{
    public function execute(Order $order): void;
}
