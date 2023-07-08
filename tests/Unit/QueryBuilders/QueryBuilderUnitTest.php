<?php

namespace QueryBuilders;

use App\Domain\Users\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryBuilderUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_contains_in_one_column(): void
    {
        $expected = User::factory(['email' => 'a'])->create();
        User::factory(['email' => 'b'])->create();

        $test = User::query()->contains(null, [])->get();
        $this->assertCount(2, $test);

        $test = User::query()->contains('a', ['email'])->get();
        $this->assertCount(1, $test);
        $this->assertEquals($expected->email, $test[0]->email);
    }

    public function test_contains_in_multiple_column(): void
    {
        $user1 = User::factory(['email' => 'a', 'password' => 'b'])->create();
        $user2 = User::factory(['email' => 'c', 'password' => 'b'])->create();

        $test = User::query()->contains('a', ['email', 'password'])->get();
        $this->assertCount(1, $test);
        $this->assertEquals($user1->email, $test[0]->email);

        $test = User::query()->contains('b', ['email', 'password'])->get()->sortBy('id');
        $this->assertCount(2, $test);
        $this->assertEquals($user1->email, $test[0]->email);
        $this->assertEquals($user2->email, $test[1]->email);
    }

    public function test_list_users_without_an_specific_user(): void
    {
        $user = User::factory()->create();
        User::factory(5)->create();

        $test = User::query()->withoutUser($user->id)->get();

        $this->assertNotContains($user, $test);
    }

    public function test_products_filter_by_category_and_brand(): void
    {
        Category::factory(2)->create();
        Brand::factory(2)->create();
        Product::factory(5)->create();
        $product = Product::query()->first();

        $expected = Product::query()->where('products.brand_id', '=', $product->brand_id)->get();
        $test = Product::query()->filterBrand([$product->brand_id])->get();
        for ($i = 0; $i < count($expected); $i++) {
            $this->assertEquals($expected[$i]->brand_id, $test[$i]->brand_id);
        }

        $expected = Product::query()->where('products.category_id', '=', $product->category_id)->get();
        $test = Product::query()->filterCategory($product->category_id)->get();
        for ($i = 0; $i < count($expected); $i++) {
            $this->assertEquals($expected[$i]->category_id, $test[$i]->category_id);
        }
    }
}
