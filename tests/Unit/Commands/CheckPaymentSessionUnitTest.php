<?php

namespace Tests\Unit\Commands;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckPaymentSessionUnitTest extends TestCase
{
    use RefreshDatabase;

    use RefreshDatabase;

    private User $user;
    private Product $product;
    private Order $order;

    public function setUp(): void
    {
        parent::setUp();

        Brand::factory()->create();
        Category::factory()->create();

        $this->user = User::factory()->create();

        $this->product = Product::factory()->create([
            'name' => 'Product 1',
            'price' => 100,
            'stock' => 3,
        ]);

        $this->order = Order::create([
            'user_id' => $this->user->getKey(),
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::PENDING,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);
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
            'id' => $this->order->getKey(),
            'status' => OrderStatus::ACCEPTED,
            'active' => false,
        ]);
    }

    public function test_command_check_payment_session_with_active_rejected_order(): void
    {
        $this->order->status = OrderStatus::REJECTED;
        $this->order->save();

        $this->artisan('app:check-payment-session');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->getKey(),
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
            'id' => $this->order->getKey(),
            'status' => OrderStatus::REJECTED,
            'active' => false,
        ]);
    }
}
