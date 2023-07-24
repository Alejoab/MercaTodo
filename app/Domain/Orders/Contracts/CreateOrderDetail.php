<?php

namespace App\Domain\Orders\Contracts;

use App\Domain\Orders\Models\Order_detail;

interface CreateOrderDetail
{
    public function execute(array $data): Order_detail;
}
