<?php

namespace App\Factories;

use App\Contracts\Payments\Payments;
use App\Enums\PaymentMethod;
use App\Services\Payment\PlaceToPay\PlaceToPayService;

class PaymentFactory
{
    public static function create(PaymentMethod $paymentMethod): Payments
    {
        switch ($paymentMethod) {
            default:
                return new PlaceToPayService();
        }
    }
}
