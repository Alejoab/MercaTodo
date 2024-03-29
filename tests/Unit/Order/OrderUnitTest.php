<?php

namespace Tests\Unit\Order;

use App\Domain\Carts\Exceptions\CartException;
use App\Domain\Carts\Services\CartsService;
use App\Domain\Orders\Actions\AcceptOrderAction;
use App\Domain\Orders\Actions\CreateOrderAction;
use App\Domain\Orders\Actions\DeleteOrderAction;
use App\Domain\Orders\Actions\RejectOrderAction;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\Order_detail;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\UserTestCase;

class OrderUnitTest extends UserTestCase
{
    use RefreshDatabase;

    private Product $product;

    public function setUp(): void
    {
        parent::setUp();

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

        Cache::put('cart:'.$this->admin->id, [$this->product->id => 3]);
    }

    public function test_can_create_an_order(): void
    {
        $action = new CreateOrderAction();
        $service = new CartsService();

        $cart = $service->getValidData($this->admin->id);
        $order = $action->execute($this->admin->id, $cart, 'PlacetoPay');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_details', 1);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->admin->id,
            'total' => 300,
        ]);

        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => 'Product 1',
            'quantity' => 3,
            'amount' => 100,
        ]);
    }

    public function test_accept_order(): void
    {
        $createOrder = new CreateOrderAction();
        $order = $createOrder->execute($this->admin->id, [$this->product->id => 3], 'PlacetoPay');

        $acceptOrder = new AcceptOrderAction();
        $acceptOrder->execute($order);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'ACCEPTED',
            'active' => false,
        ]);
    }

    public function test_reject_order_when_the_expiration_time_has_pass(): void
    {
        $createOrder = new CreateOrderAction();
        $order = $createOrder->execute($this->admin->id, [$this->product->id => 3], 'PlacetoPay');

        $this->product->refresh();
        $this->assertEquals(0, $this->product->stock);
        $this->assertNotNull($this->product->deleted_at);

        $this->travel(config('payment.expire') + 1)->minutes();

        $rejectOrder = new RejectOrderAction();
        $rejectOrder->execute($order);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::REJECTED,
            'active' => false,
        ]);

        $this->product->refresh();
        $this->assertDatabaseCount('orders', 1);
        $this->assertFalse($order->active);
        $this->assertDatabaseCount('order_details', 1);
        $this->assertEquals(3, $this->product->stock);
        $this->assertNull($this->product->deleted_at);
    }

    public function test_reject_order_when_it_is_active(): void
    {
        $createOrder = new CreateOrderAction();
        $order = $createOrder->execute($this->admin->id, [$this->product->id => 3], 'PlacetoPay');

        $this->product->refresh();
        $this->assertEquals(0, $this->product->stock);
        $this->assertNotNull($this->product->deleted_at);

        $rejectOrder = new RejectOrderAction();
        $rejectOrder->execute($order);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::REJECTED,
            'active' => true,
        ]);

        $this->travel(config('payment.expire') - 1)->minutes();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::REJECTED,
            'active' => true,
        ]);

        $this->assertDatabaseCount('order_details', 1);
        $this->assertEquals(0, $this->product->stock);
        $this->assertNotNull($this->product->deleted_at);
    }

    public function test_delete_order(): void
    {
        $createOrder = new CreateOrderAction();
        $order = $createOrder->execute($this->admin->id, [$this->product->id => 3], 'PlacetoPay');

        $this->product->refresh();
        $this->assertEquals(0, $this->product->stock);
        $this->assertNotNull($this->product->deleted_at);

        $rejectOrder = new DeleteOrderAction();
        $rejectOrder->execute($order);

        $this->product->refresh();
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_details', 0);
        $this->assertEquals(3, $this->product->stock);
        $this->assertNull($this->product->deleted_at);
    }

    public function test_try_delete_order_with_one_product_force_deleted(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $this->product->forceDelete();

        $rejectOrder = new DeleteOrderAction();
        $rejectOrder->execute($order);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_details', 0);
    }

    public function test_order_details_exists_even_product_was_deleted(): void
    {
        $action = new CreateOrderAction();
        $service = new CartsService();

        $cart = $service->getValidData($this->admin->id);
        $order = $action->execute($this->admin->id, $cart, 'PlacetoPay');

        $this->product->forceDelete();
        $this->assertDatabaseCount('order_details', 1);

        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'product_name' => 'Product 1',
            'quantity' => 3,
            'amount' => 100,
        ]);
    }

    public function test_order_details_deletes_when_the_order_was_deleted(): void
    {
        $action = new CreateOrderAction();
        $service = new CartsService();

        $cart = $service->getValidData($this->admin->id);
        $order = $action->execute($this->admin->id, $cart, 'PlacetoPay');

        $order->delete();
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_details', 0);
    }

    public function test_get_valid_cart_data(): void
    {
        $service = new CartsService();

        $data = $service->getValidData($this->admin->id);
        $expected = [
            $this->product->id => 3,
        ];

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertEquals($expected, $data);
    }

    public function test_get_valid_cart_data_with_empty_cart(): void
    {
        Cache::flush();
        $service = new CartsService();

        $this->expectException(CartException::class);
        $this->expectExceptionMessage('Your cart is empty. Please add some items to your cart before checking out.');
        $service->getValidData($this->admin->id);
    }

    public function test_get_valid_cart_data_with_deleted_product(): void
    {
        $this->product->forceDelete();
        $service = new CartsService();

        $this->expectException(CartException::class);
        $this->expectExceptionMessage('One of your products was deleted from the store.');
        $service->getValidData($this->admin->id);
    }

    public function test_get_valid_cart_data_with_product_with_insufficient_stock(): void
    {
        $this->product->stock = 2;
        $this->product->save();
        $service = new CartsService();

        $this->expectException(CartException::class);
        $this->expectExceptionMessage('The product Product 1 only has 2 items left in stock. Please remove some items from your cart.');
        $service->getValidData($this->admin->id);
    }

    public function test_get_the_order_from_the_order_details(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $orderDetail = Order_detail::query()->first();
        $this->assertEquals($order->id, $orderDetail->order->id);
    }
}
