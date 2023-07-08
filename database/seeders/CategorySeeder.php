<?php

namespace Database\Seeders;

use App\Domain\Products\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory(env('CATEGORY_SEEDER'))->create();
    }
}
