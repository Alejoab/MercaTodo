<?php

namespace Tests\Feature\Administrator;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Department;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;

    public function setUp(): void
    {
        parent::setUp();
        $roleAdmin = Role::create(['name' => 'Administrator']);
        $roleCustomer = Role::create(['name' => 'Customer']);

        Department::factory(1)->create();
        City::factory(1)->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole($roleAdmin);

        $this->customer = User::factory()->create();
        $this->customer->assignRole($roleCustomer);
    }

    /**
     * A basic feature test example.
     */
    public function test_only_admin_can_render_the_product_creation_view(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->customer)->get(route('admin.products.create'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_products(): void
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.products.create'), [
            'code' => '000001',
            'category_name' => $category->name,
            'brand_name' => $brand->name,
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'image' => null,
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));

        $product = Product::first();
        $this->assertDatabaseCount('products', 1);
        $this->assertEquals('000001', $product->code);
        $this->assertEquals($category->id, $product->category_id);
        $this->assertEquals($brand->id, $product->brand_id);
        $this->assertEquals('Product 1', $product->name);
        $this->assertEquals('Product 1 description', $product->description);
        $this->assertEquals(10.02, $product->price);
        $this->assertEquals(10, $product->stock);
    }

    public function test_admin_can_create_new_categories(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.products.create'), [
            'code' => '000001',
            'category_name' => 'Category Test',
            'brand_name' => $brand->name,
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'image' => null,
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));

        $category = Category::first();
        $this->assertDatabaseCount('categories', 1);
        $this->assertEquals('Category Test', $category->name);
    }

    public function test_admin_can_create_new_brands(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.products.create'), [
            'code' => '000001',
            'category_name' => $category->name,
            'brand_name' => 'Brand Test',
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'image' => null,
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));

        $brand = Brand::first();
        $this->assertDatabaseCount('brands', 1);
        $this->assertEquals('Brand Test', $brand->name);
    }
}
