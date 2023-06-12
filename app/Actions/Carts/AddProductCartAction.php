<?php

namespace App\Actions\Carts;

use App\Contracts\Actions\Carts\AddProductCart;
use Illuminate\Support\Facades\Cache;

class AddProductCartAction implements AddProductCart
{

    public function execute(int $userId, array $data): void
    {
        $cart = Cache::get('cart:'.$userId) ?? [];

        $cart[$data['product_id']] = $data['quantity'];
        Cache::put('cart:'.$userId, $cart, now()->addWeek());
    }
}
