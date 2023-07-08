<?php

namespace Tests\Feature\Order;

use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Department;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;


    public function setUp(): void
    {
        parent::setUp();
        $roleCustomer = Role::create(['name' => RoleEnum::CUSTOMER->value]);

        Department::factory(1)->create();
        City::factory(1)->create();

        $this->customer = User::factory()->create();
        $this->customer->assignRole($roleCustomer);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->customer)->get(route('cart'));
        $response->assertStatus(200);
    }

    public function test_only_logged_in_users_can_see_the_cart(): void
    {
        $response = $this->get(route('cart'));
        $response->assertStatus(302);
    }

    public function test_a_customer_can_add_a_product_to_the_cart(): void
    {
        Category::factory(1)->create();
        Brand::factory(1)->create();
        $product = Product::factory()->create();

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
        Category::factory(1)->create();
        Brand::factory(1)->create();
        $product = Product::factory()->create();

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
        $product = Product::factory()->create();

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
        Category::factory(1)->create();
        Brand::factory(1)->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => -1,
        ]);

        $response->assertSessionHasErrors();

        $response = $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => $product->stock + 1,
        ]);

        $response->assertSessionHasErrors();
    }
}
