<?php

namespace Tests\Unit\Administrator;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Department;
use App\Models\Product;
use App\Models\User;
use App\Services\Products\BrandsService;
use App\Services\Products\CategoriesService;
use App\Services\Products\ProductImagesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProductUnitTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();
        $roleAdmin = Role::create(['name' => 'Administrator']);

        Department::factory(1)->create();
        City::factory(1)->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole($roleAdmin);
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

    public function test_a_brand_is_not_store_twice(): void
    {
        $brandService = new BrandsService();
        $brandService->store('Brand 1');
        $this->assertDatabaseCount('brands', 1);

        $brandService->store('Brand 1');
        $this->assertDatabaseCount('brands', 1);
    }

    public function test_a_category_is_not_stored_twice(): void
    {
        $categoryService = new CategoriesService();
        $categoryService->store('Category 1');
        $this->assertDatabaseCount('categories', 1);

        $categoryService->store('Category 1');
        $this->assertDatabaseCount('categories', 1);
    }

    public function test_delete_an_image_from_the_storage(): void
    {
        $imageService = new ProductImagesService();
        Category::factory(1)->create();
        Brand::factory(1)->create();
        $product = Product::factory()->create();

        $this->assertFileExists(storage_path('app/public/product_images/'.$product->image));

        $imageService->deleteImage($product->image);
        $this->assertFileDoesNotExist(storage_path('app/public/product_images/'.$product->image));
    }

    public function tearDown(): void
    {
        File::delete(File::allFiles(storage_path('app/public/product_images')));
        parent::tearDown();
    }
}
