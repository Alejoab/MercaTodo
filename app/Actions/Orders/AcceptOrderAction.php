<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\AcceptOrder;
use App\Enums\OrderStatus;
use App\Exceptions\ApplicationException;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Throwable;

class AcceptOrderAction implements AcceptOrder
{

    /**
     * @throws ApplicationException
     */
    public function execute(Order $order): void
    {
        try {
            $order->status = OrderStatus::ACCEPTED;
            $order->active = false;
            $order->save();

            Log::info("[ORDER-ACCEPTED]", ['orderId' => $order->id,]);
        } catch (Throwable $e) {
            Log::error("[ERROR] [ORDER-NO-APPROVED]", ['orderId' => $order->id,]);
            throw new ApplicationException($e, $order->toArray());
        }
    }
}
