<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\UpdateOrder;
use App\Exceptions\ApplicationException;
use App\Models\Order;
use Throwable;

class UpdateOrderAction implements UpdateOrder
{

    /**
     * @throws ApplicationException
     */
    public function execute(Order $order, array $data): void
    {
        try {
            $order->fill($data);
            $order->save();
        } catch (Throwable $e) {
            throw new ApplicationException($e);
        }
    }
}
