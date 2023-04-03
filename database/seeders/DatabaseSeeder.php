<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * Insert the cities, states and roles in the database
         */
        $this->call([
            DepartmentsCitiesSeeder::class,
            RolesSeeder::class
        ]);

        /**
         * Create the initial admin user in the database.
         */
        User::factory()->create([
            'name' => env('ADMIN_NAME'),
            'surname' => env('ADMIN_SURNAME'),
            'document_type' => env('ADMIN_DOCUMENT_TYPE'),
            'document' => env('ADMIN_DOCUMENT'),
            'email' => env('ADMIN_EMAIL'),
            'phone' => env('ADMIN_PHONE'),
            'address' => env('ADMIN_ADDRESS'),
            'password' => Hash::make(env('ADMIN_PASSWORD')),
            'city_id' => env('ADMIN_CITY_ID'),
        ])->assignRole('Administrator');

        /**
         * Create a random users in the database with the role of customer.
         */
        User::factory(200)->create()->each(function ($user) {
            $user->assignRole('Customer');
        });
    }
}
