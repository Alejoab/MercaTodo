<?php

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Contracts\CreateOrder;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Enums\PaymentMethod;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateOrderAction implements CreateOrder
{

    /**
     * @throws CustomException
     */
    public function execute(int $userId, array $cart, string $method): Order
    {
        try {
            DB::beginTransaction();

            $createOrderDetailAction = new CreateOrderDetailAction();
            /**
             * @var Order $order
             */
            $order = Order::query()->create([
                'code' => 'ORD-'.time(),
                'user_id' => $userId,
                'status' => OrderStatus::PENDING,
                'payment_method' => PaymentMethod::tryFrom($method) ?? PaymentMethod::PLACE_TO_PAY,
            ]);
            $total = 0;
            foreach ($cart as $product_id => $quantity) {
                $orderDetail = $createOrderDetailAction->execute([
                    'order_id' => $order->getKey(),
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                ]);
                $total += $orderDetail->subtotal;
            }
            $order->total = $total;
            $order->save();

            DB::commit();

            return $order;
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                throw $e;
            }

            throw new ApplicationException($e, [
                'userId' => $userId,
                'cart' => $cart,
                'method' => $method,
            ]);
        }
    }
}
