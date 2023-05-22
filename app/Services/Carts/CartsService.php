<?php

namespace App\Services\Carts;

use App\Models\Product;
use Illuminate\Support\Facades\Redis;

class CartsService
{
    public function getCart(int $userId)
    {
        $cart = Redis::command('hgetall', ['cart:'.$userId]) ?? [];

        foreach ($cart as $product_id => $quantity) {
            $product = Product::query()->find($product_id);

            if (!$product) {
                Redis::command('hdel', ['cart:'.$userId, $product_id]);
                unset($cart[$product_id]);
            }
        }

        return $cart;
    }

    public function getNumberOfItems(int $userId): int
    {
        $cart = Redis::command('hgetall', ['cart:'.$userId]) ?? [];

        return count($cart);
    }
}
