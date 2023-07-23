<?php

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Contracts\DeleteOrder;
use App\Domain\Orders\Models\Order;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use Illuminate\Support\Facades\Log;
use Throwable;

class DeleteOrderAction implements DeleteOrder
{

    /**
     * @throws CustomException
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
