<?php

namespace App\Actions\Carts;

use App\Contracts\Actions\Carts\DeleteProductCart;
use Illuminate\Support\Facades\Redis;

class DeleteProductCartAction implements DeleteProductCart
{

    public function execute(int $userId, array $data): void
    {
        Redis::command('hdel', [
            'cart:'.$userId,
            $data['product_id'],
        ]);
    }
}
