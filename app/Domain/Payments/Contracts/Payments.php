<?php

namespace App\Domain\Payments\Contracts;


use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Users\Models\User;
use Illuminate\Http\Request;

interface Payments
{
    public function paymentProcess(Request $request, User $user, Order $order): string;

    public function checkPayment(Order $order): OrderStatus;
}
