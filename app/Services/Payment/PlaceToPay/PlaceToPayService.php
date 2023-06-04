<?php

namespace App\Services\Payment\PlaceToPay;

use App\Actions\Orders\AcceptOrderAction;
use App\Actions\Orders\DeleteOrderAction;
use App\Actions\Orders\RejectOrderAction;
use App\Actions\Orders\UpdateOrderAction;
use App\Contracts\Payments\Payments;
use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PlaceToPayService implements Payments
{
    /**
     * @throws PaymentException
     */
    public function paymentProcess(Request $request): string
    {
        /**
         * @var Order $order
         */
        $order = Order::query()->getLast($request->user()->getKey());

        /**
         * @var User $user
         */
        $user = User::query()->find($request->user()->getKey());

        $paymentSession = $this->createPaymentSession($user, $order, $request->ip(), $request->userAgent());
        $result = Http::post(config('placetopay.url').'/api/session', $paymentSession);

        if ($result->ok()) {
            $action = new UpdateOrderAction();

            $data = [
                'requestId' => $result->json()['requestId'],
                'processUrl' => $result->json()['processUrl'],
            ];

            $action->execute($order, $data);

            Cache::forget('cart:'.$request->user()->getKey());

            return $result->json()['processUrl'];
        } else {
            $action = new DeleteOrderAction();
            $action->execute($order);

            if ($result->json()['status']['reason'] === 401) {
                throw PaymentException::authError();
            }
            throw PaymentException::sessionError($result->json()['status']['message']);
        }
    }


    public function checkPayment(Request $request, Order $order): void
    {
        $auth = new Auth();

        $result = Http::post(config('placetopay.url')."/api/session/$order->requestId", [
            'auth' => $auth->getAuth(),
        ]);

        if ($result->ok()) {
            $this->paymentStatus($order, $result->json()['status']['status']);
        }
    }

    private function paymentStatus(Order $order, string $status): void
    {
        switch ($status) {
            case 'APPROVED':
                $action = new AcceptOrderAction();
                $action->execute($order);
                break;
            case 'REJECTED':
                $action = new RejectOrderAction();
                $action->execute($order);
                break;
            case 'PENDING':
                break;
        }
    }
}
