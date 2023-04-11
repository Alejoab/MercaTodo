<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        ]);
    }
}
