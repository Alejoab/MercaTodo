<?php

namespace App\Contracts\Payments;


use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

interface Payments
{
    public function paymentProcess(Request $request, User $user, Order $order): string;

    public function checkPayment(Order $order): OrderStatus;
}
