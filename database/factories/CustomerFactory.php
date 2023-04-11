<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\City;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_type' => fake()->randomElement(DocumentType::cases()),
            'document' => fake()->unique()->numberBetween(
                1000000000,
                9999999999
            ),
            'phone' => fake()->numberBetween(1000000000, 9999999999),
            'address' => fake()->address(),
            'city_id' => fake()->randomElement(City::all())->id,
        ];
    }
}
