<?php

namespace Database\Factories;

use App\Domain\Products\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Domain\Products\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucwords(
                strtolower(
                    $this->faker->unique()->randomElement(
                        [
                            'Fruits',
                            'Meat',
                            'Bakery',
                            'Dairy',
                            'Drinks',
                            'Vegetables',
                            'Canned',
                            'Frozen',
                            'Snacks',
                            'Beverages',
                            'Alcohol',
                            'Seafood',
                            'Bread',
                            'Pasta',
                            'Rice',
                            'Eggs',
                            'Sauces',
                            'Spices',
                            'Oils',
                            'Condiments',
                            'Sweets',
                            'Nuts',
                            'Dried',
                            'Baby',
                            'Pet',
                            'Cleaning',
                            'Health',
                            'Beauty',
                            'Pharmacy',
                            'Office',
                            'School',
                            'Home',
                            'Garden',
                            'Tools',
                            'Electronics',
                            'Toys',
                            'Games',
                            'Sports',
                            'Outdoors',
                            'Automotive',
                            'Industrial',
                            'Books',
                            'Music',
                            'Movies',
                            'Clothing',
                            'Shoes',
                            'Jewelry',
                            'Watches',
                            'Accessories',
                            'Luggage',
                            'Handbags',
                            'Wallets',
                            'Travel',
                            'Grocery',
                            'Liquor',
                            'Gifts',
                            'Flowers',
                            'Hobbies',
                            'Crafts',
                            'Party',
                            'Supplies',
                            'Tobacco',
                            'Other',
                        ]
                    )
                )
            ),
        ];
    }
}
