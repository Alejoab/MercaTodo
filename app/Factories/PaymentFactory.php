<?php

namespace App\Factories;

use App\Contracts\Payments;
use App\Enums\PaymentMethod;
use App\Services\Payment\PlaceToPay\PlaceToPayService;

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
