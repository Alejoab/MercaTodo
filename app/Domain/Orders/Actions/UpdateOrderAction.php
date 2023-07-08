<?php

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Contracts\UpdateOrder;
use App\Domain\Orders\Models\Order;
use App\Support\Exceptions\ApplicationException;
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
            throw new ApplicationException($e, [
                'order' => $order->toArray(),
                'data' => $data,
            ]);
        }
    }
}
