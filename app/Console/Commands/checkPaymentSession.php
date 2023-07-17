<?php

namespace App\Console\Commands;

use App\Domain\Orders\Actions\AcceptOrderAction;
use App\Domain\Orders\Actions\RejectOrderAction;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Factories\PaymentFactory;
use App\Support\Exceptions\ApplicationException;
use Illuminate\Console\Command;

class checkPaymentSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payment-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Checks the payment session when the order is pending and monitors the status of the order while it is active.
     *
     * @return void
     * @throws ApplicationException
     */
    public function handle(): void
    {
        $orders = Order::query()->whereActive()->get();

        /**
         * @var Order $order
         */
        foreach ($orders as $order) {
            if ($order->status !== OrderStatus::PENDING) {
                if ($order->created_at->diffInMinutes(now()) >= config('payment.expire')) {
                    $action = new RejectOrderAction();
                    $action->execute($order);
                }

                continue;
            }


            $paymentService = PaymentFactory::create($order->payment_method);
            $status = $paymentService->checkPayment($order);

            try {
                switch ($status) {
                    case OrderStatus::ACCEPTED:
                        $action = new AcceptOrderAction();
                        $action->execute($order);
                        break;
                    case OrderStatus::REJECTED:
                        $action = new RejectOrderAction();
                        $action->execute($order);
                        break;
                    default:
                        break;
                }
            } catch (ApplicationException) {
                return;
            }
        }
    }
}
