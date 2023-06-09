<?php

namespace App\Services\Payment\PlaceToPay;

use App\Actions\Orders\UpdateOrderAction;
use App\Contracts\Payments\Payments;
use App\Enums\OrderStatus;
use App\Exceptions\ApplicationException;
use App\Exceptions\CustomException;
use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class PlaceToPayService implements Payments
{
    /**
     * @throws ApplicationException
     * @throws CustomException
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
            throw new ApplicationException($e);
        }
    }

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
            'expiration' => Carbon::now()->addMinutes(config('placetopay.expirationLink')),
            'returnUrl' => route('payment.success'),
            'cancelUrl' => route('payment.cancel'),
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
        ];
    }

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

    private function paymentStatus(string $status): OrderStatus
    {
        return match ($status) {
            'APPROVED' => OrderStatus::ACCEPTED,
            'REJECTED' => OrderStatus::REJECTED,
            default => OrderStatus::PENDING,
        };
    }
}
