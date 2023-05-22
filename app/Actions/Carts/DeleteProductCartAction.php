<?php

namespace App\Actions\Carts;

use App\Contracts\Actions\Carts\DeleteProductCart;
use Illuminate\Support\Facades\Redis;

class DeleteProductCartAction implements DeleteProductCart
{

    public function execute(int $userId, array $data): void
    {
        $cart = json_decode(Redis::command('get', ['cart:'.$userId]));
        $key = array_search($data['product_id'], array_column($cart, 'id'));

        unset($cart[$key]);
        Redis::command('set', ['cart:'.$userId, json_encode($cart)]);
    }
}
