<?php

namespace App\Domain\Orders\Contracts;

use App\Domain\Orders\Models\Order;

interface RestoreProducts
{
    public function execute(Order $order): void;
}
