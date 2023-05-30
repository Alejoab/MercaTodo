<?php

namespace Tests\Unit\Order;

use App\Actions\Carts\AddProductCartAction;
use App\Actions\Carts\DeleteProductCartAction;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\Carts\CartsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class CartUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_product_to_cart(): void
    {
        $action = new AddProductCartAction();

        $action->execute(1, [
            'product_id' => 1,
            'quantity' => 1,
        ]);

        $data = Redis::command('hgetall', ['cart:1']);
        $this->assertCount(1, $data);
        $this->assertEquals(
            [
                1 => 1,
            ]
            , $data
        );

        $action->execute(1, [
            'product_id' => 2,
            'quantity' => 1,
        ]);

        $data = Redis::command('hgetall', ['cart:1']);
        $this->assertCount(2, $data);
        $this->assertEquals(
            [
                1 => 1,
                2 => 1,
            ]
            , $data
        );
    }

    public function test_update_product_in_cart(): void
    {
        $action = new AddProductCartAction();

        $action->execute(1, [
            'product_id' => 1,
            'quantity' => 1,
        ]);

        $data = Redis::command('hgetall', ['cart:1']);
        $this->assertCount(1, $data);
        $this->assertEquals(
            [
                1 => 1,
            ]
            , $data
        );

        $action->execute(1, [
            'product_id' => 1,
            'quantity' => 2,
        ]);

        $data = Redis::command('hgetall', ['cart:1']);
        $this->assertCount(1, $data);
        $this->assertEquals(
            [
                1 => 2,
            ]
            , $data
        );
    }

    public function test_remove_product_from_cart(): void
    {
        $action = new AddProductCartAction();

        $action->execute(1, [
            'product_id' => 1,
            'quantity' => 1,
        ]);

        $action->execute(1, [
            'product_id' => 2,
            'quantity' => 1,
        ]);

        $data = Redis::command('hgetall', ['cart:1']);
        $this->assertCount(2, $data);

        $action = new DeleteProductCartAction();
        $action->execute(1, ['product_id' => 2,]);

        $data = Redis::command('hgetall', ['cart:1']);
        $this->assertCount(1, $data);
        $this->assertEquals(
            [
                1 => 1,
            ]
            , $data
        );
    }

    public function test_get_cart_with_products(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $action = new AddProductCartAction();
        $service = new CartsService();

        $action->execute(1, [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $data = $service->getCartWithProducts(1);
        $product = Product::query()->find($product->id)->toArray();
        $product['quantity'] = 1;
        $this->assertCount(1, $data);
        $this->assertEquals(
            [
                $product,
            ]
            , $data
        );
    }

    public function test_only_get_enable_items_from_the_cart(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $action = new AddProductCartAction();
        $service = new CartsService();

        $action->execute(1, [
            'product_id' => $product1->id,
            'quantity' => 1,
        ]);
        $action->execute(1, [
            'product_id' => $product2->id,
            'quantity' => 1,
        ]);
        $product2->delete();

        $data = Redis::command('hgetall', ['cart:1']);
        $this->assertCount(2, $data);

        $data = $service->getCartWithProducts(1);
        $this->assertCount(1, $data);
    }
}
