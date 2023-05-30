<?php

namespace App\Actions\Carts;

use App\Contracts\Actions\Carts\AddProductCart;
use Illuminate\Support\Facades\Redis;

class AddProductCartAction implements AddProductCart
{

    public function execute(int $userId, array $data): void
    {
        Redis::command('hset', [
            'cart:'.$userId,
            $data['product_id'],
            $data['quantity'],
        ]);
    }
}
