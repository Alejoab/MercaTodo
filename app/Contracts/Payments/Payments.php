<?php

namespace App\Contracts\Payments;


use App\Models\Order;
use Illuminate\Http\Request;

interface Payments
{
    public function paymentProcess(Request $request): string;

    public function checkPayment(Request $request, Order $order): void;
}
