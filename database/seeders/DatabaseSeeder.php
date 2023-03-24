<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\DocumentType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $path = base_path() . '/database/seeders/cities_states.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);

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
        ]);

        User::factory(200)->create();
    }
}
