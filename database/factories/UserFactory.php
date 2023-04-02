<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
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
            'document' => fake()->unique()->numberBetween(
                1000000000,
                9999999999
            ),
            'document_type' => fake()->randomElement(DocumentType::cases()),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numberBetween(1000000000, 9999999999),
            'address' => fake()->address(),
            'city_id' => fake()->randomElement(City::select('id')->get())->id,
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
