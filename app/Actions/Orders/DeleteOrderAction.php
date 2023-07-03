<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\DeleteOrder;
use App\Exceptions\ApplicationException;
use App\Models\Order;
use App\Models\Order_detail;
use Illuminate\Support\Facades\Log;
use Throwable;

class DeleteOrderAction implements DeleteOrder
{

    /**
     * @throws ApplicationException
     */
    public function execute(Order $order): void
    {
        try {
            (new RestoreProductsAction())->execute($order);
            $order->forceDelete();
        } catch (Throwable $e) {
            Log::error("[ERROR] [ORDER-NO-DELETED]", ['orderId' => $order->id]);
            throw new ApplicationException($e, $order->toArray());
        }
    }
}
