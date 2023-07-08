<?php

namespace Database\Seeders;

use App\Domain\Products\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(env('PRODUCT_SEEDER'))->create();
    }
}
