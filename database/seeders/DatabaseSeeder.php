<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\DocumentType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * Insert the cities and the states in the database from a sql file with
         * an insert statement.
         */
        $path = base_path() . '/database/seeders/cities_departments.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        /**
         * Create the initial roles in the database.
         */
        Role::create(['name' => 'Administrator']);
        Role::create(['name' => 'Customer']);

        /**
         * Create the initial admin user in the database.
         */
        User::factory()->create([
            'name' => 'Alejandro',
            'surname' => 'Alvarez',
            'document' => '12345678',
            'document_type' => DocumentType::CEDULA_CIUDADANIA,
            'email' => 'alejo@alejo.com',
            'phone' => '3003003030',
            'address' => 'Calle 1 # 2 - 3',
            'password' => Hash::make('alejo1234'),
            'city_id' => 1,
        ])->assignRole('Administrator');

        /**
         * Create a random users in the database with the role of customer.
         */
        User::factory(200)->create();
    }
}
