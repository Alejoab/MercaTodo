<?php

namespace App\Actions\Carts;

use App\Contracts\Actions\Carts\DeleteProductCart;
use Illuminate\Support\Facades\Cache;

class DeleteProductCartAction implements DeleteProductCart
{

    public function execute(int $userId, array $data): void
    {
        $cart = Cache::get('cart:'.$userId) ?? [];

        unset($cart[$data['product_id']]);
        Cache::put('cart:'.$userId, $cart, now()->addWeek());
    }
}
