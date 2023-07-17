<?php

namespace Support;

use App\Domain\Products\Actions\CreateBrandAction;
use App\Domain\Products\Actions\CreateCategoryAction;
use App\Domain\Products\Models\Brand;
use App\Domain\Products\Models\Category;
use App\Domain\Products\Services\BrandsService;
use App\Domain\Products\Services\CategoriesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_cache_is_actualized_when_a_new_brand_is_stored(): void
    {
        $brandCount = Brand::query()->count();
        $brandAction = new CreateBrandAction();
        $brandService = new BrandsService();

        $this->assertCount($brandCount, $brandService->brandsByCategory(null));
        $this->assertTrue(Cache::has('brands_by_category_all'));

        $brandAction->execute('Brand Cache Test');
        $this->assertEquals('Brand Cache Test', Brand::query()->first()->name);
        $this->assertNotTrue(Cache::has('brands_by_category_all'));

        $this->assertCount($brandCount + 1, $brandService->brandsByCategory(null));
        $this->assertTrue(Cache::has('brands_by_category_all'));
    }

    public function test_category_cache_is_actualized_when_a_new_category_is_stored(): void
    {
        $categoryCount = Category::query()->count();
        $categoryAction = new CreateCategoryAction();
        $categoryService = new CategoriesService();

        $this->assertCount($categoryCount, $categoryService->list());
        $this->assertTrue(Cache::has('categories'));

        $categoryAction->execute('Category Cache Test');
        $this->assertEquals('Category Cache Test', Category::query()->first()->name);
        $this->assertNotTrue(Cache::has('categories'));

        $this->assertCount($categoryCount + 1, $categoryService->list());
        $this->assertTrue(Cache::has('categories'));
    }
}
