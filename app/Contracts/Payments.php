<?php

namespace App\Contracts;


use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

interface Payments
{
    /**
     * Creates the user's payment session to the order and return the process url
     *
     * @param Request                         $request
     * @param User                            $user
     * @param \App\Domain\Orders\Models\Order $order
     *
     * @return string
     */
    public function paymentProcess(Request $request, User $user, Order $order): string;

    /**
     * Checks the status of the payment session
     *
     * @param Order $order
     *
     * @return \App\Domain\Orders\Enums\OrderStatus
     */
    public function checkPayment(Order $order): OrderStatus;
}
