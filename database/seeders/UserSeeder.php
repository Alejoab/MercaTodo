<?php

namespace Database\Seeders;

use App\Domain\Customers\Enums\DocumentType;
use App\Domain\Customers\Models\Customer;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
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
        ])->assignRole(RoleEnum::SUPER_ADMIN->value);

        Customer::factory()->create([
            'name' => env('ADMIN_NAME'),
            'surname' => '',
            'document_type' => DocumentType::CC,
            'document' => '',
            'phone' => '',
            'address' => '',
            'city_id' => 1,
            'user_id' => $adminUser->id,
        ]);

        /**
         * Create a random users in the database with the role of customer.
         */
        User::factory()->count(config('seeders.users'))->has(Customer::factory())->create()->each(function ($user) {
            $user->assignRole(RoleEnum::CUSTOMER->value);
        });
    }
}
