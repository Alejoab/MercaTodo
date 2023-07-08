<?php

namespace App\Domain\Payments\Factories;

use App\Domain\Payments\Contracts\Payments;
use App\Domain\Payments\Enums\PaymentMethod;
use App\Domain\Payments\PlaceToPay\PlaceToPayService;

class PaymentFactory
{
    /**
     * Returns the payment service according to the payment method
     *
     * @param PaymentMethod $paymentMethod
     *
     * @return \App\Domain\Orders\Contracts\\App\Contracts\Payments
     */
    public static function create(PaymentMethod $paymentMethod): Payments
    {
        return match ($paymentMethod) {
            default => new PlaceToPayService(),
        };
    }
}
