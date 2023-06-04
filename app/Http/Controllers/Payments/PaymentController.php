<?php

namespace App\Http\Controllers\Payments;

use App\Contracts\Actions\Orders\CreateOrder;
use App\Factories\PaymentFactory;
use App\Http\Controllers\Controller;
use App\Services\Carts\CartsService;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @throws Exception
     */
    public function pay(Request $request, CreateOrder $orderAction, CartsService $cartService): string
    {
        $userId = $request->user()->id;
        $cart = $cartService->getValidData($userId);

        $order = $orderAction->execute($userId, $cart, 'PlaceToPay');
        $paymentService = PaymentFactory::create($order->payment_method);

        return $paymentService->paymentProcess($request);
    }


}
