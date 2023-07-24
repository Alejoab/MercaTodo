<?php

namespace App\Domain\Payments\Factories;

use App\Domain\Payments\Contracts\Payments;
use App\Domain\Payments\Enums\PaymentMethod;
use App\Domain\Payments\PlaceToPay\PlaceToPayService;

class PaymentFactory
{
    public static function create(PaymentMethod $paymentMethod): Payments
    {
        return match ($paymentMethod) {
            default => new PlaceToPayService(),
        };
    }
}
