<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\CreateOrderDetail;
use App\Exceptions\ApplicationException;
use App\Models\Order_detail;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateOrderDetailAction implements CreateOrderDetail
{
    /**
     * @throws ApplicationException
     */
    public function execute(array $data): Order_detail
    {
        try {
            /**
             * @var Product $product
             */
            $product = Product::query()->find($data['product_id']);
            $subtotal = $product->price * $data['quantity'];
            /**
             * @var Order_detail $oderDetail
             */
            $oderDetail = Order_detail::query()->create([
                'order_id' => $data['order_id'],
                'product_id' => $data['product_id'],
                'product_code' => $product->code,
                'product_name' => $product->name,
                'quantity' => $data['quantity'],
                'amount' => $product->price,
                'subtotal' => $subtotal,
            ]);
            $product->stock -= $data['quantity'];
            $product->save();
            if ($product->stock === 0) {
                $product->delete();
                Log::info("[PRODUCT-NO-STOCK]", ['productID' => $product->id,]);
            }

            return $oderDetail;
        } catch (Throwable $e) {
            throw new ApplicationException($e);
        }
    }
}
