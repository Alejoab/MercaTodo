<?php

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Contracts\AcceptOrder;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Exceptions\ApplicationException;
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
