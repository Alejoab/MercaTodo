<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order;

interface RestoreProducts
{
    public function execute(Order $order): void;
}
