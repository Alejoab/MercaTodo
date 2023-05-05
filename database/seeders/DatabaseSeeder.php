<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

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
            DepartmentCitySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        Cache::flush();
    }
}
