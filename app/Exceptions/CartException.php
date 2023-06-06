<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class CartException extends CustomException
{
    public static function empty(): self
    {
        return new self(__('validation.custom.cart.empty'));
    }

    public static function invalidStock($product, $stock): self
    {
        return new self(__('validation.custom.cart.stock', ['product' => $product, 'stock' => $stock,]));
    }

    public static function deletedProduct(): self
    {
        return new self(__('validation.custom.cart.deleted'));
    }
}
