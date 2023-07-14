<?php

namespace Tests\Feature\Order;

use App\Domain\Customers\Models\Customer;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Enums\PaymentMethod;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\UserTestCase;

class OrderTest extends UserTestCase
{
    use RefreshDatabase;

    private Product $product;

    public function setUp(): void
    {
        parent::setUp();

        Customer::factory()->create([
            'user_id' => $this->customer->id,
        ]);

        Brand::factory()->create();
        Category::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create([
            'name' => 'Product 1',
            'price' => 100,
            'stock' => 3,
        ]);

        $this->product = $product;
        Cache::put('cart:'.$this->customer->id, [$this->product->id => 3]);

        $this->actingAs($this->customer);
    }

    public function test_buy(): void
    {
        Http::fake(
            [
                config('placetopay.url').'/api/session' => [
                    "status" => [
                        "status" => "OK",
                        "reason" => "PC",
                        "message" => "La petición se ha procesado correctamente",
                        "date" => "2021-11-30T15:08:27-05:00",
                    ],
                    "requestId" => 1,
                    "processUrl" => "https://test-route.com",
                ],
            ]
        );

        $response = $this->post(route('cart.buy'), [
            'paymentMethod' => 'PlaceToPay',
        ]);

        $response->assertRedirect("https://test-route.com");
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_details', 1);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'total' => 300,
            'status' => 'Pending',
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);
    }

    public function test_accept_payment(): void
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

        Order::query()->create([
            'user_id' => $this->customer->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::PENDING,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);

        $response = $this->get(route('payment.success'));
        $response->assertOk();

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::ACCEPTED,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);
    }

    public function test_retry_payment_when_the_session_is_still_active(): void
    {
        /**
         * @var Order $order
         */
        $order = Order::query()->create([
            'user_id' => $this->customer->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::PENDING,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);

        $response = $this->post(route('payment.retry'), [
            'orderId' => $order->id,
        ]);

        $response->assertRedirect('https://test-route.com');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $this->customer->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::PENDING,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);
    }

    public function test_retry_payment_when_the_session_is_expired(): void
    {
        Http::fake(
            [
                config('placetopay.url').'/api/session' => [
                    "status" => [
                        "status" => "OK",
                        "reason" => "PC",
                        "message" => "La petición se ha procesado correctamente",
                        "date" => "2021-11-30T15:08:27-05:00",
                    ],
                    "requestId" => 100,
                    "processUrl" => "https://test-route-session-expired.com",
                ],
            ]
        );

        /**
         * @var Order $order
         */
        $order = Order::query()->create([
            'user_id' => $this->customer->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::REJECTED,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);

        $response = $this->post(route('payment.retry'), [
            'orderId' => $order->id,
        ]);

        $response->assertRedirect("https://test-route-session-expired.com");

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $this->customer->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::PENDING,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 100,
            'processUrl' => 'https://test-route-session-expired.com',
        ]);
    }

    public function test_retry_payment_when_order_is_not_active(): void
    {
        /**
         * @var Order $order
         */
        $order = Order::query()->create([
            'user_id' => $this->customer->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::REJECTED,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'active' => false,
            'processUrl' => 'https://test-route.com',
        ]);

        $response = $this->post(route('payment.retry'), [
            'orderId' => $order->id,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_retry_payment_when_the_user_is_not_the_owner_of_it(): void
    {
        /**
         * @var User $user2
         */
        $user2 = User::factory()->create();

        /**
         * @var Order $order
         */
        $order = Order::query()->create([
            'user_id' => $user2->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::REJECTED,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'active' => false,
            'processUrl' => 'https://test-route.com',
        ]);

        $response = $this->post(route('payment.retry'), [
            'orderId' => $order->id,
        ]);

        $response->assertSessionHasErrors(['payment' => __('validation.custom.payment.order_not_found')]);
    }

    public function test_cancel_payment(): void
    {
        Order::query()->create([
            'user_id' => $this->customer->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::PENDING,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);

        $response = $this->get(route('payment.cancel'));
        $response->assertOk();

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_try_buy_with_an_active_session(): void
    {
        Order::query()->create([
            'user_id' => $this->customer->id,
            'code' => '123456',
            'total' => 300,
            'status' => OrderStatus::PENDING,
            'payment_method' => PaymentMethod::PLACE_TO_PAY,
            'requestId' => 1,
            'processUrl' => 'https://test-route.com',
        ]);

        $response = $this->post(route('cart.buy'), [
            'paymentMethod' => 'PlaceToPay',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_try_access_success_route_without_an_payment_active_session(): void
    {
        $response = $this->get(route('payment.success'));
        $response->assertRedirect(route('home'));
    }

    public function test_try_access_cancel_route_without_an_payment_active_session(): void
    {
        $response = $this->get(route('payment.cancel'));
        $response->assertRedirect(route('home'));
    }

    public function test_try_to_buy_a_cart_with_a_deleted_product(): void
    {
        $this->product->forceDelete();
        $response = $this->post(route('cart.buy'), [
            'paymentMethod' => 'PlaceToPay',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_try_to_buy_a_cart_with_empty_cart(): void
    {
        Cache::flush();
        $response = $this->post(route('cart.buy'), [
            'paymentMethod' => 'PlaceToPay',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_try_to_buy_a_cart_with_an_invalid_quantity(): void
    {
        $this->product->stock = 2;
        $this->product->save();
        $response = $this->post(route('cart.buy'), [
            'paymentMethod' => 'PlaceToPay',
        ]);

        $response->assertSessionHasErrors();
    }
}
