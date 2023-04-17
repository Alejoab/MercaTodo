<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[0-9]{6}'),
            'category_id' => $this->faker->randomElement(Category::all())->id,
            'brand_id' => $this->faker->randomElement(Brand::all())->id,
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'image' => $this->faker->image(public_path('product_images'), 640, 480, null, false),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'stock' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
