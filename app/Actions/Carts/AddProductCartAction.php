<?php

namespace App\Actions\Carts;

use App\Contracts\Actions\Carts\AddProductCart;
use Illuminate\Support\Facades\Redis;

class AddProductCartAction implements AddProductCart
{

    public function execute(int $userId, array $data): void
    {
        $cart = json_decode(Redis::command('get', ['cart:'.$userId]), true) ?? [];
        $key = array_search($data['product_id'], array_column($cart, 'id'));

        if ($key !== false) {
            $cart[$key]['quantity'] = $data['quantity'];
        } else {
            $cart[] = [
                'id' => $data['product_id'],
                'quantity' => $data['quantity'],
            ];
        }

        Redis::command('set', ['cart:'.$userId, json_encode($cart)]);
    }
}
