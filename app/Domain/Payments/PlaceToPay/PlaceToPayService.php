<?php

namespace App\Domain\Payments\PlaceToPay;

use App\Domain\Orders\Actions\UpdateOrderAction;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Contracts\Payments;
use App\Domain\Payments\Exceptions\PaymentException;
use App\Domain\Users\Models\User;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class PlaceToPayService implements Payments
{

    /**
     * Creates a new placetopay payment session and returns the process url
     *
     * @param Request                       $request
     * @param \App\Domain\Users\Models\User $user
     * @param Order                         $order
     *
     * @return string
     * @throws \App\Support\Exceptions\ApplicationException
     * @throws CustomException
     * @throws \App\Domain\Payments\Exceptions\PaymentException
     */
    public function paymentProcess(Request $request, User $user, Order $order): string
    {
        try {
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

                return $order->processUrl;
            } else {
                if ($result->json()['status']['reason'] === 401) {
                    throw PaymentException::authError();
                }

                throw PaymentException::sessionError($result->json()['status']['message']);
            }
        } catch (CustomException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ApplicationException($e, [
                'request' => $request->getContent(),
                'user' => $user->toArray(),
                'order' => $order->toArray(),
            ]);
        }
    }

    /**
     * Creates the data that will be sent to placetopay
     *
     * @param User   $user
     * @param Order  $order
     * @param string $ipAddress
     * @param string $userAgent
     *
     * @return array
     */
    private function createPaymentSession(User $user, Order $order, string $ipAddress, string $userAgent): array
    {
        $auth = new Auth();
        $buyer = new Buyer($user);
        $payment = new OrderToPayment($order);

        return [
            'locale' => 'en_CO',
            'auth' => $auth->getAuth(),
            'buyer' => $buyer->getBuyer(),
            'payment' => $payment->getPayment(),
            'expiration' => Carbon::now()->addMinutes(config('placetopay.expirationSession')),
            'returnUrl' => route('payment.success'),
            'cancelUrl' => route('payment.cancel'),
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
        ];
    }

    /**
     * Checks the payment session status
     *
     * @param Order $order
     *
     * @return OrderStatus
     */
    public function checkPayment(Order $order): OrderStatus
    {
        $auth = new Auth();

        $result = Http::post(config('placetopay.url')."/api/session/$order->requestId", [
            'auth' => $auth->getAuth(),
        ]);

        if ($result->ok()) {
            return $this->paymentStatus($result->json()['status']['status']);
        } else {
            return OrderStatus::PENDING;
        }
    }

    /**
     * Matches the placetopay status with the application status
     *
     * @param string $status
     *
     * @return OrderStatus
     */
    private function paymentStatus(string $status): OrderStatus
    {
        return match ($status) {
            'APPROVED' => OrderStatus::ACCEPTED,
            'REJECTED' => OrderStatus::REJECTED,
            default => OrderStatus::PENDING,
        };
    }
}
