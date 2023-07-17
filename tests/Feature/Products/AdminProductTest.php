<?php

namespace Products;

use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\UserTestCase;

class AdminProductTest extends UserTestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_render_the_product_creation_view(): void
    {
        $response = $this->get(route('admin.products.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->customer)->get(route('admin.products.create'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_products(): void
    {
        Category::factory(['name' => 'Category Test'])->create();
        Brand::factory(['name' => 'Brand Test'])->create();

        $response = $this->post(route('admin.products.create'), [
            'code' => '000001',
            'category_name' => 'Category Test',
            'brand_name' => 'Brand Test',
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'image' => UploadedFile::fake()->image('image.jpg'),
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));

        $this->assertDatabaseHas('products', [
            'code' => '000001',
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'price' => 10.02,
            'stock' => 10,
        ]);

        Storage::disk('product_images')->assertExists(Product::query()->first()->getAttribute('image'));
    }

    public function test_admin_can_create_new_categories(): void
    {
        Brand::factory(['name' => 'Brand Test'])->create();

        $response = $this->post(route('admin.products.create'), [
            'code' => '000001',
            'category_name' => 'Category Test',
            'brand_name' => 'Brand Test',
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'image' => UploadedFile::fake()->image('image.png'),
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'name' => 'Category Test',
        ]);
    }

    public function test_admin_can_create_new_brands(): void
    {
        Category::factory(['name' => 'Category Test'])->create();

        $response = $this->post(route('admin.products.create'), [
            'code' => '000001',
            'category_name' => 'Category Test',
            'brand_name' => 'Brand Test',
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'image' => UploadedFile::fake()->image('image.jpg'),
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));

        $this->assertDatabaseCount('brands', 1);
        $this->assertDatabaseHas('brands', [
            'name' => 'Brand Test',
        ]);
    }

    public function test_only_admin_can_update_a_product(): void
    {
        Brand::factory()->create();
        Category::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create();

        $response = $this->get(route('admin.products.show', $product->id));
        $response->assertStatus(200);

        $response = $this->actingAs($this->customer)->get(route('admin.products.show', $product->id));
        $response->assertStatus(403);
    }

    public function test_only_created_products_can_be_rendered(): void
    {
        $response = $this->get(route('admin.products.show', -1));
        $response->assertStatus(404);
    }

    public function test_admin_can_update_a_product(): void
    {
        Brand::factory()->create();
        Category::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create();

        $response = $this->post(route('admin.products.update', $product->id), [
            'code' => '000001',
            'category_name' => 'Category Update Test',
            'brand_name' => 'Brand Update Test',
            'name' => 'Product 1 Updated',
            'description' => 'Product 1 description Updated',
            'image' => null,
            'price' => 5.1,
            'stock' => 1000,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products.show', $product->id));

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseHas('products', [
            'code' => '000001',
            'name' => 'Product 1 Updated',
            'description' => 'Product 1 description Updated',
            'price' => 5.1,
            'stock' => 1000,
        ]);

        $this->assertDatabaseCount('categories', 2);
        $this->assertDatabaseHas('categories', [
            'name' => 'Category Update Test',
        ]);

        $this->assertDatabaseCount('brands', 2);
        $this->assertDatabaseHas('brands', [
            'name' => 'Brand Update Test',
        ]);
    }

    public function test_admin_can_delete_a_product(): void
    {
        Brand::factory()->create();
        Category::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create();

        $this->delete(route('admin.products.destroy', $product->id));

        $this->assertNotNull($product->fresh()->deleted_at);
    }

    public function test_admin_can_restore_a_product(): void
    {
        Brand::factory()->create();
        Category::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create();
        $product->delete();

        $this->put(route('admin.products.restore', $product->id));

        $this->assertNull($product->fresh()->deleted_at);
    }

    public function test_admin_can_force_delete_a_product(): void
    {
        Brand::factory()->create();
        Category::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create();
        $product->delete();

        $this->delete(route('admin.products.force-delete', $product->id));

        $this->assertDatabaseCount('products', 0);
    }

    public function test_when_force_delete_a_product_its_image_are_deleted(): void
    {
        Brand::factory()->create();
        Category::factory()->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->create();

        $this->delete(route('admin.products.force-delete', $product->id));

        $this->assertDatabaseCount('products', 0);
        Storage::disk('product_images')->assertMissing($product->image);
    }

    public function test_creation_product_form_has_expected_fields(): void
    {
        $response = $this->post(route('admin.products.create'), [
            'code' => '00000001',
            'category_name' => '',
            'brand_name' => '$brand->name',
            'name' => '',
            'description' => '',
            'image' => 'a',
            'price' => -1,
            'stock' => -1,
        ]);

        $response->assertSessionHasErrors();
    }
}
