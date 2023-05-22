<?php

namespace App\Services\Carts;

use App\Models\Product;
use Illuminate\Support\Facades\Redis;

class CartsService
{
    public function getCart(int $userId)
    {
        $cart = json_decode(Redis::command('get', ['cart:'.$userId]), true) ?? [];
        $result = [];

        foreach ($cart as $item) {
            $product = Product::query()->find($item['id']);

            if ($product) {
                $result[] = $item;
            }
        }

        Redis::command('set', ['cart:'.$userId, json_encode($result)]);

        return $result;
    }

    public function getNumberOfItems(int $userId): int
    {
        $cart = json_decode(Redis::command('get', ['cart:'.$userId]), true) ?? [];

        return count($cart);
    }
}
