<?php

namespace App\Services\Carts;

use App\Actions\Carts\DeleteProductCartAction;
use App\Exceptions\CartException;
use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Cache;

class CartsService
{
    public function getCartWithProducts(int $userId): array
    {
        $action = new DeleteProductCartAction();
        $cart = Cache::get('cart:'.$userId) ?? [];
        $products = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::query()->find($productId);
            if (!$product) {
                $action->execute($userId, ['product_id' => $productId]);
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
        return count(Cache::get('cart:'.$userId) ?? []);
    }

    /**
     * @throws Exception
     */
    public function getValidData(int $userId): array
    {
        if (Order::query()->getLast($userId) !== null) {
            throw PaymentException::sessionActive();
        }

        $action = new DeleteProductCartAction();
        $cart = Cache::get('cart:'.$userId);

        if (!$cart) {
            throw CartException::empty();
        }

        foreach ($cart as $product_id => $quantity) {
            $product = Product::query()->find($product_id);

            if (!$product) {
                $action->execute($userId, ['product_id' => $product_id]);
                throw CartException::deletedProduct();
            } elseif ($product->getAttribute('stock') < $quantity) {
                throw CartException::invalidStock($product->getAttribute('name'), $product->getAttribute('stock'));
            }
        }

        return $cart;
    }
}
