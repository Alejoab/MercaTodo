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
            $order->save();
        } catch (Throwable $e) {
            Log::warning("[ERROR] [ORDER-NO-APPROVED] orderId $order->id");
            throw new ApplicationException($e);
        }
    }
}
