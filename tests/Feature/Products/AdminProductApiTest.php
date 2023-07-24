<?php

namespace Tests\Feature\Products;

use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UserTestCase;

class AdminProductApiTest extends UserTestCase
{
    use RefreshDatabase;

    public function test_admin_can_login(): void
    {
        $response = $this->postJson(route('api.login'), [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['message', 'data']);
    }

    public function test_only_admin_can_create_new_products(): void
    {
        $this->actingAs($this->customer);

        $response = $this->postJson(route('api.admin.products.store'), [
            'code' => '000001',
            'category_name' => 'Category test',
            'brand_name' => 'Brand test',
            'name' => 'Product 1',
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_products_brands_and_categories(): void
    {
        $response = $this->postJson(route('api.admin.products.store'), [
            'code' => '000001',
            'category_name' => 'Category test',
            'brand_name' => 'Brand test',
            'name' => 'Product 1',
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'code' => '000001',
            'name' => 'Product 1',
            'price' => 10.02,
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Category test',
        ]);

        $this->assertDatabaseHas('brands', [
            'name' => 'Brand test',
        ]);
    }

    public function test_only_admin_can_update_a_product(): void
    {
        $this->actingAs($this->customer);
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->putJson(route('api.admin.products.update', $product->id), [
            'code' => '000001',
            'category_name' => 'Category test',
            'brand_name' => 'Brand test',
            'name' => 'Product 1',
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_a_product(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->putJson(route('api.admin.products.update', $product->id), [
            'code' => '000001',
            'category_name' => 'Category test',
            'brand_name' => 'Brand test',
            'name' => 'Product 1',
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('products', [
            'code' => '000001',
            'name' => 'Product 1',
            'price' => 10.02,
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Category test',
        ]);

        $this->assertDatabaseHas('brands', [
            'name' => 'Brand test',
        ]);
    }

    public function test_only_admin_can_delete_a_product(): void
    {
        $this->actingAs($this->customer);
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('api.admin.products.destroy', $product->id));

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_a_product(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('api.admin.products.destroy', $product->id));
        $product->refresh();

        $response->assertOk();
        $this->assertNotNull($product->deleted_at);
    }

    public function test_only_admin_can_restore_a_product(): void
    {
        $this->actingAs($this->customer);

        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->putJson(route('api.admin.products.restore', $product->id));
        $response->assertStatus(403);
    }

    public function test_admin_can_restore_a_product(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->putJson(route('api.admin.products.restore', $product->id));
        $product->refresh();

        $response->assertOk();
        $this->assertNull($product->deleted_at);
    }

    public function test_only_admin_can_force_delete_a_product(): void
    {
        $this->actingAs($this->customer);

        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('api.admin.products.force-delete', $product->id));
        $response->assertStatus(403);
    }

    public function test_admin_can_force_delete_a_product(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('api.admin.products.force-delete', $product->id));
        $response->assertOk();
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_only_admin_can_list_products(): void
    {
        $this->actingAs($this->customer);

        $response = $this->getJson(route('api.admin.products.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_list_products(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        Product::factory()->count(11)->create();

        $response = $this->getJson(route('api.admin.products.index'));
        $response->assertOk();
        $response->assertJsonStructure(['message', 'data']);
        $response->assertJsonStructure(['data', 'current_page', 'last_page', 'per_page', 'total'], $response['data']);
    }

    public function test_only_admin_can_show_products(): void
    {
        $this->actingAs($this->customer);

        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->getJson(route('api.admin.products.show', $product->id));
        $response->assertStatus(403);
    }

    public function test_admin_can_show_products(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->getJson(route('api.admin.products.show', $product->id));
        $response->assertOk();
        $response->assertJsonStructure(['message', 'data']);
        $response->assertJsonStructure(['id', 'code', 'name'], $response['data']);
    }

    public function test_only_created_products_can_be_updated(): void
    {
        $response = $this->putJson(route('api.admin.products.update', 1), [
            'code' => '000001',
            'category_name' => 'Category test',
            'brand_name' => 'Brand test',
            'name' => 'Product 1',
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertStatus(404);
    }

    public function test_only_created_products_can_be_deleted(): void
    {
        $response = $this->deleteJson(route('api.admin.products.destroy', 1));

        $response->assertStatus(404);
    }

    public function test_only_created_products_can_be_restored(): void
    {
        $response = $this->putJson(route('api.admin.products.restore', 1));

        $response->assertStatus(404);
    }

    public function test_only_created_products_can_be_force_deleted(): void
    {
        $response = $this->deleteJson(route('api.admin.products.force-delete', 1));

        $response->assertStatus(404);
    }

    public function test_status_404_when_product_not_found(): void
    {
        $response = $this->getJson(route('api.admin.products.show', -1));

        $response->assertNotFound();
    }
}
