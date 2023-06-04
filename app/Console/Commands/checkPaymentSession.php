<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
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

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $orders = Order::query()->whereStatus(OrderStatus::PENDING)->get();

        /**
         * @var Order $order
         */
        foreach ($orders as $order) {
            $paymentService = PaymentFactory::create($order->payment_method);
            $paymentService->checkPayment($order);
        }
    }
}
