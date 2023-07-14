<?php

namespace Database\Seeders;

use App\Domain\Products\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::factory(config('seeders.brands'))->create();
    }
}
