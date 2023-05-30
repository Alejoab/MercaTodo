<?php

namespace App\Contracts\Actions\Orders;

use App\Models\Order_detail;

interface CreateOrderDetail
{
    public function execute(array $data): Order_detail;
}
