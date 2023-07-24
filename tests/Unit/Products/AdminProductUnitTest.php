<?php

namespace Tests\Unit\Products;

use App\Domain\Products\Actions\CreateBrandAction;
use App\Domain\Products\Actions\CreateCategoryAction;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Services\ProductImagesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\UserTestCase;

class AdminProductUnitTest extends UserTestCase
{
    use RefreshDatabase;

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

    public function test_get_all_products_from_a_brand_and_from_a_category(): void
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();

        /**
         * @var Brand $brand
         */
        $brand = Brand::factory()->create();
        Product::factory(3)->create(['brand_id' => $brand->id]);

        $this->assertCount(3, $brand->products);
        $this->assertCount(3, $category->products);
    }
}
