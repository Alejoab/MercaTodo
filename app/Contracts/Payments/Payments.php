<?php

namespace App\Contracts\Payments;


use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

interface Payments
{
    /**
     * Creates the user's payment session to the order
     *
     * @param Request $request
     * @param User    $user
     * @param Order   $order
     *
     * @return string
     */
    public function paymentProcess(Request $request, User $user, Order $order): string;

    /**
     * Checks the status of the payment session
     *
     * @param Order $order
     *
     * @return OrderStatus
     */
    public function checkPayment(Order $order): OrderStatus;
}
