<?php

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Contracts\RejectOrder;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Log;
use Throwable;

class RejectOrderAction implements RejectOrder
{

    /**
     * @throws ApplicationException
     */
    public function execute(Order $order): void
    {
        try {
            $order->status = OrderStatus::REJECTED;
            $order->save();

            if ($order->created_at->diffInMinutes(now()) < config('payment.expire')) {
                return;
            }

            (new RestoreProductsAction())->execute($order);

            $order->active = false;
            $order->save();
        } catch (Throwable $e) {
            Log::error("[ERROR] [ORDER-NO-REJECTED]", ['orderId' => $order->id,]);
            throw new ApplicationException($e, $order->toArray());
        }
    }
}
