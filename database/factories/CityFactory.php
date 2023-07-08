<?php

namespace Database\Factories;

use App\Domain\Customers\Models\City;
use App\Domain\Customers\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->city(),
            'department_id' => fake()->randomElement(
                Department::all()
            )->id,
        ];
    }
}
