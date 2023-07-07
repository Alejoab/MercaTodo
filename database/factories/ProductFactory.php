<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
        $color = '#'.str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        $image = Image::canvas(800, 800)->fill($color)->encode('jpg');
        $imageName = Str::random(40).'.jpg';

        Storage::disk('product_images')->put("$imageName", $image);

        return [
            'code' => $this->faker->unique()->randomNumber(6, true),
            'category_id' => Category::query()->inRandomOrder()->first()->getAttribute('id'),
            'brand_id' => Brand::query()->inRandomOrder()->first()->getAttribute('id'),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'image' => $imageName,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'stock' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
