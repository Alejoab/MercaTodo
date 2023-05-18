<?php

namespace Tests\Feature\Administrator;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Department;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            'image' => UploadedFile::fake()->image('image.jpg'),
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
        Storage::disk('product_images')->assertExists($product->image);
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
            'image' => UploadedFile::fake()->image('image.png'),
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
            'image' => UploadedFile::fake()->image('image.jpg'),
            'price' => 10.02,
            'stock' => 10,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));

        $brand = Brand::first();
        $this->assertDatabaseCount('brands', 1);
        $this->assertEquals('Brand Test', $brand->name);
    }

    public function test_only_admin_can_update_a_product(): void
    {
        Brand::factory(1)->create();
        Category::factory(1)->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.products.show', $product->id));
        $response->assertStatus(200);

        $response = $this->actingAs($this->customer)->get(route('admin.products.show', $product->id));
        $response->assertStatus(403);
    }

    public function test_only_created_products_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.show', -1));
        $response->assertStatus(404);
    }

    public function test_admin_can_update_a_product(): void
    {
        Brand::factory(1)->create();
        Category::factory(1)->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.products.update', $product->id), [
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

        $product->refresh();

        $this->assertEquals('000001', $product->code);
        $this->assertEquals('Category Update Test', $product->category->name);
        $this->assertEquals('Brand Update Test', $product->brand->name);
        $this->assertEquals('Product 1 Updated', $product->name);
        $this->assertEquals('Product 1 description Updated', $product->description);
        $this->assertEquals(5.1, $product->price);
        $this->assertEquals(1000, $product->stock);
        $this->assertNotNull($product->image);
    }

    public function test_admin_can_delete_a_product(): void
    {
        Brand::factory(1)->create();
        Category::factory(1)->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product->id));

        $this->assertNotNull($product->fresh()->deleted_at);
    }

    public function test_admin_can_restore_a_product(): void
    {
        Brand::factory(1)->create();
        Category::factory(1)->create();
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->actingAs($this->admin)->put(route('admin.products.restore', $product->id));

        $this->assertNull($product->fresh()->deleted_at);
    }

    public function test_admin_can_forcedelete_a_product(): void
    {
        Brand::factory(1)->create();
        Category::factory(1)->create();
        $product = Product::factory()->create();
        $product->delete();

        $response = $this->actingAs($this->admin)->delete(route('admin.products.force-delete', $product->id));

        $this->assertDatabaseCount('products', 0);
    }

    public function test_when_forcedelete_a_product_its_image_are_deleted(): void
    {
        Brand::factory(1)->create();
        Category::factory(1)->create();
        $product = Product::factory()->create();

        $this->actingAs($this->admin)->delete(route('admin.products.force-delete', $product->id));

        $this->assertDatabaseCount('products', 0);
        Storage::disk('product_images')->assertMissing($product->image);
    }

    public function test_creation_product_form_has_expected_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.create'), [
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
