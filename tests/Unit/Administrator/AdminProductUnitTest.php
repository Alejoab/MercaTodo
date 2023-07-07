<?php

namespace Tests\Unit\Administrator;

use App\Actions\Products\CreateBrandAction;
use App\Actions\Products\CreateCategoryAction;
use App\Enums\RoleEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Department;
use App\Models\Product;
use App\Models\User;
use App\Services\Products\ProductImagesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProductUnitTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();
        $roleAdmin = Role::create(['name' => RoleEnum::SUPER_ADMIN->value]);

        Department::factory(1)->create();
        City::factory(1)->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole($roleAdmin);
    }

    public function test_a_brand_is_not_store_twice(): void
    {
        $brandAction = new CreateBrandAction();
        $brandAction->execute('Brand 1');
        $this->assertDatabaseCount('brands', 1);

        $brandAction->execute('Brand 1');
        $this->assertDatabaseCount('brands', 1);
    }

    public function test_a_category_is_not_stored_twice(): void
    {
        $categoryAction = new CreateCategoryAction();
        $categoryAction->execute('Category 1');
        $this->assertDatabaseCount('categories', 1);

        $categoryAction->execute('Category 1');
        $this->assertDatabaseCount('categories', 1);
    }

    public function test_delete_an_image_from_the_storage(): void
    {
        $imageService = new ProductImagesService();
        Category::factory(1)->create();
        Brand::factory(1)->create();
        $product = Product::factory()->create();

        Storage::disk('product_images')->assertExists($product->image);

        $imageService->deleteImage($product->image);
        Storage::disk('product_images')->assertMissing($product->image);
    }
}
