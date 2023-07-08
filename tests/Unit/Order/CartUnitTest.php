<?php

namespace Tests\Unit\Order;

use App\Domain\Carts\Actions\AddProductCartAction;
use App\Domain\Carts\Actions\DeleteProductCartAction;
use App\Domain\Carts\Services\CartsService;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
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

        $data = Cache::get('cart:1');
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

        $data = Cache::get('cart:1');
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

        $data = Cache::get('cart:1');
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

        $data = Cache::get('cart:1');
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

        $data = Cache::get('cart:1');
        $this->assertCount(2, $data);

        $action = new DeleteProductCartAction();
        $action->execute(1, ['product_id' => 2,]);

        $data = Cache::get('cart:1');
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

        $data = Cache::get('cart:1');
        $this->assertCount(2, $data);

        $data = $service->getCartWithProducts(1);
        $this->assertCount(1, $data);
    }
}
