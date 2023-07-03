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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
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
     * @throws PaymentException
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
     * @param Request     $request
     * @param DeleteOrder $deleteOrderAction
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
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws PaymentException
     */
    public function retry(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $request->validate([
            'orderId' => ['required', Rule::exists('orders', 'id')],
        ]);

        /**
         * @var ?Order $order
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
