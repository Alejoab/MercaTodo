<?php

namespace App\Actions\Orders;

use App\Contracts\Actions\Orders\CreateOrderDetail;
use App\Models\Order_detail;
use App\Models\Product;

class CreateOrderDetailAction implements CreateOrderDetail
{
    public function execute(array $data): Order_detail
    {
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
        }

        return $oderDetail;
    }
}
