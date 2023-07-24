<?php

namespace App\Http\Controllers\Web\Payments;

use App\Domain\Carts\Services\CartsService;
use App\Domain\Orders\Actions\AcceptOrderAction;
use App\Domain\Orders\Actions\DeleteOrderAction;
use App\Domain\Orders\Actions\RejectOrderAction;
use App\Domain\Orders\Contracts\CreateOrder;
use App\Domain\Orders\Contracts\DeleteOrder;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Exceptions\PaymentException;
use App\Domain\Payments\Factories\PaymentFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayRequest;
use App\Http\Requests\RetryPaymentRequest;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class PaymentController extends Controller
{
    /**
     * @throws CustomException
     */
    public function pay(PayRequest $request, CreateOrder $createOrderAction, CartsService $cartService): \Symfony\Component\HttpFoundation\Response
    {
        $userId = $request->user()->id;
        if (Order::query()->getLast($userId) !== null) {
            throw PaymentException::sessionActive();
        }

        $cart = $cartService->getValidData($userId);
        $order = $createOrderAction->execute($userId, $cart, $request->validated(['paymentMethod']));

        try {
            $paymentService = PaymentFactory::create($order->payment_method);
            $url = $paymentService->paymentProcess($request, $request->user(), $order);
        } catch (Throwable $e) {
            (new DeleteOrderAction())->execute($order);

            if ($e instanceof CustomException) {
                throw $e;
            }

            throw new ApplicationException($e, []);
        }

        return Inertia::location($url);
    }

    /**
     * @throws CustomException
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
                (new AcceptOrderAction())->execute($order);
                break;
            case OrderStatus::REJECTED:
                (new RejectOrderAction())->execute($order);
                break;
            case OrderStatus::PENDING:
                break;
        }

        return Inertia::render('Order/SuccessOrder', [
            'order' => $order,
            'status' => $order->status,
        ]);
    }

    public function cancel(Request $request, DeleteOrder $deleteOrderAction): Response|RedirectResponse
    {
        /**
         * @var ?Order $order
         */
        $order = Order::query()->getLast($request->user()->getKey());

        if (!$order) {
            return Redirect::to(route('home'));
        }

        $deleteOrderAction->execute($order);

        return Inertia::render('Order/CancelOrder');
    }

    /**
     * @throws CustomException
     */
    public function retry(RetryPaymentRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        /**
         * @var ?Order $order
         */
        $order = Order::query()->whereUser($request->user()->id)->find($request->get('orderId'));

        if (!$order) {
            throw PaymentException::orderNotFound();
        }
        if (!$order->active) {
            throw PaymentException::orderNotActive();
        }
        if ($order->status === OrderStatus::PENDING) {
            return Inertia::location($order->processUrl);
        }
        if (Order::query()->getLast($request->user()->id) !== null) {
            throw PaymentException::sessionActive();
        }

        $order->status = OrderStatus::PENDING;
        $order->save();
        $paymentService = PaymentFactory::create($order->payment_method);
        $url = $paymentService->paymentProcess($request, $request->user(), $order);

        return Inertia::location($url);
    }
}
