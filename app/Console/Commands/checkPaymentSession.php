<?php

namespace App\Console\Commands;

use App\Actions\Orders\AcceptOrderAction;
use App\Actions\Orders\RejectOrderAction;
use App\Enums\OrderStatus;
use App\Exceptions\ApplicationException;
use App\Factories\PaymentFactory;
use App\Models\Order;
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

    public function handle(): void
    {
        $orders = Order::query()->whereStatus(OrderStatus::PENDING)->get();

        /**
         * @var Order $order
         */
        foreach ($orders as $order) {
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
