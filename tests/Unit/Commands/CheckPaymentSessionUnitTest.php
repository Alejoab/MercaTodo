<?php

namespace Tests\Unit\Commands;

use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Enums\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\UserTestCase;

class CheckPaymentSessionUnitTest extends UserTestCase
{
    use RefreshDatabase;

    private Order $order;

    public function setUp(): void
    {
        parent::setUp();

        /**
         * @var Order $order
         */
        $order = Order::query()->create([
            'user_id' => $this->admin->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::PENDING,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);
        $this->order = $order;
    }

    public function test_command_check_payment_session()
    {
        Http::fake(
            [
                config('placetopay.url')."/api/session/1" => [
                    "requestId" => 1,
                    "status" => [
                        "status" => "APPROVED",
                        "reason" => "00",
                        "message" => "La petición ha sido aprobada exitosamente",
                        "date" => "2022-07-27T14:51:27-05:00",
                    ],
                ],
            ]
        );

        $this->artisan('app:check-payment-session');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => OrderStatus::ACCEPTED,
            'active' => false,
        ]);
    }

    public function test_command_check_payment_session_with_rejected_order()
    {
        $this->travel(config('payment.expire') + 1)->minutes();

        Http::fake(
            [
                config('placetopay.url')."/api/session/1" => [
                    "requestId" => 1,
                    "status" => [
                        "status" => "REJECTED",
                        "reason" => "00",
                        "message" => "La petición ha sido aprobada exitosamente",
                        "date" => "2022-07-27T14:51:27-05:00",
                    ],
                ],
            ]
        );

        $this->artisan('app:check-payment-session');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => OrderStatus::REJECTED,
            'active' => false,
        ]);
    }

    public function test_command_check_payment_session_with_active_rejected_order(): void
    {
        $this->order->status = OrderStatus::REJECTED;
        $this->order->save();

        $this->artisan('app:check-payment-session');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => OrderStatus::REJECTED,
            'active' => true,
        ]);
    }

    public function test_command_check_payment_session_with_inactive_rejected_order(): void
    {
        $this->order->status = OrderStatus::REJECTED;
        $this->order->save();

        $this->travel(config('payment.expire') + 1)->minutes();
        $this->artisan('app:check-payment-session');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => OrderStatus::REJECTED,
            'active' => false,
        ]);
    }
}
