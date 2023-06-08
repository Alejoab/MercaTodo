<?php

namespace App\Http\Controllers\Payments;

use App\Contracts\Actions\Orders\CreateOrder;
use App\Contracts\Actions\Orders\DeleteOrder;
use App\Factories\PaymentFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayRequest;
use App\Models\Order;
use App\Services\Carts\CartsService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    /**
     * @throws Exception
     */
    public function pay(PayRequest $request, CreateOrder $orderAction, CartsService $cartService): \Symfony\Component\HttpFoundation\Response
    {
        $userId = $request->user()->id;
        $cart = $cartService->getValidData($userId);

        $order = $orderAction->execute($userId, $cart, $request->validated(['paymentMethod']));
        $paymentService = PaymentFactory::create($order->payment_method);

        return Inertia::location($paymentService->paymentProcess($request));
    }

    public function success(Request $request): RedirectResponse
    {
        /**
         * @var ?Order $order
         */
        $order = Order::query()->getLast($request->user()->getKey());

        if (!$order) {
            return Redirect::to(route('home'));
        }

        $paymentService = PaymentFactory::create($order->payment_method);

        $paymentService->checkPayment($order);

        return Redirect::to(route('order.history'));
    }

    public function cancel(Request $request, DeleteOrder $action): Response|RedirectResponse
    {
        /**
         * @var ?Order $order
         */
        $order = Order::query()->getLast($request->user()->getKey());

        if (!$order) {
            return Redirect::to(route('home'));
        }

        $action->execute($order);

        return Inertia::render('Order/CancelOrder');
    }
}
