<?php

namespace App\Exceptions;

class CartException extends CustomException
{

    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->sessionErrorName = 'payment';
    }

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
