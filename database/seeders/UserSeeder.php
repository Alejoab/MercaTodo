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
        $adminCustomer = Customer::factory()->create([
            'document_type' => env('ADMIN_DOCUMENT_TYPE'),
            'document' => env('ADMIN_DOCUMENT'),
            'phone' => env('ADMIN_PHONE'),
            'address' => env('ADMIN_ADDRESS'),
            'city_id' => env('ADMIN_CITY_ID'),
        ]);

        User::factory()->create([
            'name' => env('ADMIN_NAME'),
            'surname' => env('ADMIN_SURNAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => Hash::make(env('ADMIN_PASSWORD')),
            'customer_id' => $adminCustomer->id,
        ])->assignRole('Administrator');

        /**
         * Create a random users in the database with the role of customer.
         */
        for ($i = 0; $i < 200; $i++) {
            $customer = Customer::factory()->create();
            $user = User::factory()->create();
            $user->customer_id = $customer->id;
            $user->save();
            $user->assignRole('Customer');
        }
    }
}
