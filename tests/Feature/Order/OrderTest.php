<?php

namespace Tests\Feature\Order;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;

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

        Redis::command('hset', ['cart:'.$this->user->getKey(), $this->product->getKey(), 3]);
    }

    public function test_buy_a_cart(): void
    {
        $response = $this->actingAs($this->user)->post(route('cart.buy'));

        $response->assertOk();
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_details', 1);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->getKey(),
            'total' => 300,
        ]);
    }

    public function test_try_to_buy_a_cart_with_a_deleted_product(): void
    {
        $this->product->forceDelete();
        $response = $this->actingAs($this->user)->post(route('cart.buy'));

        $response->assertStatus(400);
    }

    public function test_try_to_buy_a_cart_with_empty_cart(): void
    {
        Redis::command('flushdb');
        $response = $this->actingAs($this->user)->post(route('cart.buy'));

        $response->assertStatus(400);
    }

    public function test_try_to_buy_a_cart_with_an_invalid_quantity(): void
    {
        $this->product->stock = 2;
        $this->product->save();
        $response = $this->actingAs($this->user)->post(route('cart.buy'));

        $response->assertStatus(400);
    }
}
