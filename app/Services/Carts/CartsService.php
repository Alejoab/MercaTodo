<?php

namespace App\Services\Carts;

use App\Exceptions\CartEmptyException;
use App\Exceptions\CartException;
use App\Exceptions\CartInvalidStockException;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Redis;

class CartsService
{
    public function getCartWithProducts(int $userId): array
    {
        $cart = Redis::command('hgetall', ['cart:'.$userId]) ?? [];
        $products = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::query()->find($productId);
            if (!$product) {
                Redis::command('hdel', ['cart:'.$userId, $productId]);
                continue;
            }

            $product = $product->toArray();
            $product['quantity'] = $quantity;
            $products[] = $product;
        }

        return $products;
    }

    public function getNumberOfItems(int $userId): int
    {
        return Redis::command('hlen', ['cart:'.$userId]);
    }

    /**
     * @throws Exception
     */
    public function getValidData(int $userId): array
    {
        $cart = Redis::command('hgetall', ['cart:'.$userId]);

        if (!$cart) {
            throw CartException::empty();
        }

        foreach ($cart as $product_id => $quantity) {
            $product = Product::query()->withTrashed()->find($product_id);

            if (!$product || $product->getAttribute('deleted_at') !== null) {
                Redis::command('hdel', ['cart:'.$userId, $product_id]);
                throw CartException::deletedProduct();
            } elseif ($product->getAttribute('stock') < $quantity) {
                throw CartException::invalidStock($product->getAttribute('name'), $product->getAttribute('stock'));
            }
        }

        return $cart;
    }
}
