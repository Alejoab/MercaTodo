<?php

namespace Carts;

use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tests\UserTestCase;

class CartTest extends UserTestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->customer)->get(route('cart'));
        $response->assertStatus(200);
    }

    public function test_only_logged_in_users_can_see_the_cart(): void
    {
        Auth::logout();
        $response = $this->get(route('cart'));
        $response->assertStatus(302);
    }

    public function test_a_customer_can_add_a_product_to_the_cart(): void
    {
        Category::factory()->create();
        Brand::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create(['stock' => 100]);

        $response = $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertOk();

        $data = Cache::get('cart:'.$this->customer->id);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertEquals([
            $product->id => 1,
        ], $data);
    }

    public function test_a_customer_can_update_a_product_in_the_cart(): void
    {
        Category::factory()->create();
        Brand::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create(['stock' => 100]);

        $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $data = Cache::get('cart:'.$this->customer->id);
        $this->assertCount(1, $data);
        $this->assertEquals([
            $product->id => 1,
        ], $data);

        $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $data = Cache::get('cart:'.$this->customer->id);
        $this->assertCount(1, $data);
        $this->assertEquals([
            $product->id => 5,
        ], $data);
    }

    public function test_a_customer_can_delete_a_product_from_the_cart(): void
    {
        Category::factory(1)->create();
        Brand::factory(1)->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create(['stock' => 100]);

        $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $data = Cache::get('cart:'.$this->customer->id);
        $this->assertCount(1, $data);

        $this->actingAs($this->customer)->delete(route('cart.delete'), [
            'product_id' => $product->id,
        ]);

        $data = Cache::get('cart:'.$this->customer->id);
        $this->assertCount(0, $data);
    }

    public function test_dont_add_a_invalid_product_to_a_cart(): void
    {
        $response = $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_id' => -1,
            'quantity' => 1,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_dont_add_a_product_with_invalid_quantity(): void
    {
        Category::factory()->create();
        Brand::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create(['stock' => 100]);

        $response = $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => -1,
        ]);

        $response->assertSessionHasErrors();

        $response = $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 101,
        ]);

        $response->assertSessionHasErrors();
    }
}
