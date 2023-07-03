<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\CreateOrder;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Exceptions\ApplicationException;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateOrderAction implements CreateOrder
{

    /**
     * @throws ApplicationException
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
        } catch (ApplicationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new ApplicationException($e, [
                'userId' => $userId,
                'cart' => $cart,
                'method' => $method,
            ]);
        }
    }
}
