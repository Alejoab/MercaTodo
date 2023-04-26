<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Create the initial admin user in the database.
         */
        $adminUser = User::factory()->create([
            'email' => env('ADMIN_EMAIL'),
            'password' => Hash::make(env('ADMIN_PASSWORD')),
        ])->assignRole('Administrator');

        Customer::factory()->create([
            'name' => env('ADMIN_NAME'),
            'surname' => env('ADMIN_SURNAME'),
            'document_type' => env('ADMIN_DOCUMENT_TYPE'),
            'document' => env('ADMIN_DOCUMENT'),
            'phone' => env('ADMIN_PHONE'),
            'address' => env('ADMIN_ADDRESS'),
            'city_id' => env('ADMIN_CITY_ID'),
            'user_id' => $adminUser->id,
        ]);


        /**
         * Create a random users in the database with the role of customer.
         */
        User::factory(env('USER_SEEDER'))->create()->each(function ($user) {
            $user->assignRole('Customer');
            Customer::factory()->create(['user_id' => $user->id]);
        });
    }
}
