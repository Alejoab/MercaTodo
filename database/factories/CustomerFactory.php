<?php

namespace Database\Factories;

use App\Domain\Customers\Enums\DocumentType;
use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'surname' => fake()->lastName(),
            'document_type' => fake()->randomElement(DocumentType::cases()),
            'document' => fake()->unique()->randomNumber(6, true),
            'address' => fake()->address(),
            'city_id' => fake()->randomElement(City::all())->id,
        ];
    }
}
