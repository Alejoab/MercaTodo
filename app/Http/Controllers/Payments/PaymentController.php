<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Orders\AcceptOrderAction;
use App\Actions\Orders\DeleteOrderAction;
use App\Actions\Orders\RejectOrderAction;
use App\Contracts\Actions\Orders\CreateOrder;
use App\Contracts\Actions\Orders\DeleteOrder;
use App\Enums\OrderStatus;
use App\Exceptions\ApplicationException;
use App\Exceptions\PaymentException;
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
use Throwable;

class PaymentController extends Controller
{
    /**
     * @throws Exception
     * @throws Throwable
     */
    public function pay(PayRequest $request, CreateOrder $orderAction, CartsService $cartService): \Symfony\Component\HttpFoundation\Response
    {
        $userId = $request->user()->id;
        if (Order::query()->getLast($userId) !== null) {
            throw PaymentException::sessionActive();
        }

        $cart = $cartService->getValidData($userId);
        $order = $orderAction->execute($userId, $cart, $request->validated(['paymentMethod']));

        try {
            $paymentService = PaymentFactory::create($order->payment_method);
            $url = $paymentService->paymentProcess($request, $request->user(), $order);
        } catch (Throwable $e) {
            $action = new DeleteOrderAction();
            $action->execute($order);
            throw $e;
        }

        return Inertia::location($url);
    }

    /**
     * @throws ApplicationException
     */
    public function success(Request $request): Response|RedirectResponse
    {
        /**
         * @var ?Order $order
         */
        $order = Order::query()->getLast($request->user()->getKey());

        if (!$order) {
            return Redirect::to(route('home'));
        }

        $paymentService = PaymentFactory::create($order->payment_method);

        $status = $paymentService->checkPayment($order);

        switch ($status) {
            case OrderStatus::ACCEPTED:
                $action = new AcceptOrderAction();
                $action->execute($order);
                break;
            case OrderStatus::REJECTED:
                $action = new RejectOrderAction();
                $action->execute($order);
                break;
            case OrderStatus::PENDING:
                break;
        }

        return Inertia::render('Order/SuccessOrder', [
            'status' => $order->status,
        ]);
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
