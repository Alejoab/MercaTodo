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
use App\Http\Controllers\Web\Controller;
use App\Http\Requests\PayRequest;
use App\Http\Requests\RetryPaymentRequest;
use App\Support\Exceptions\ApplicationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class PaymentController extends Controller
{

    /**
     * Redirects the user to the payment session
     *
     * @param PayRequest   $request
     * @param CreateOrder  $createOrderAction
     * @param CartsService $cartService
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \App\Domain\Payments\Exceptions\PaymentException
     * @throws Throwable
     * @throws ApplicationException
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
            throw $e;
        }

        return Inertia::location($url);
    }


    /**
     * Checks the payment status
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     * @throws \App\Support\Exceptions\ApplicationException
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


    /**
     * Cancels the payment session
     *
     * @param Request                                  $request
     * @param \App\Domain\Orders\Contracts\DeleteOrder $deleteOrderAction
     *
     * @return Response|RedirectResponse
     */
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
     * @param RetryPaymentRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \App\Domain\Payments\Exceptions\PaymentException
     */
    public function retry(RetryPaymentRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        /**
         * @var ?\App\Domain\Orders\Models\Order $order
         */
        $order = Order::query()->whereUser($request->user()->id)->find($request->get('orderId'));

        if (!$order) {
            throw PaymentException::orderNotFound();
        }
        if ($order->status === OrderStatus::PENDING) {
            return Inertia::location($order->processUrl);
        }
        if (!$order->active) {
            throw PaymentException::orderNotActive();
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
