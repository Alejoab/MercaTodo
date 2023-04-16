<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::directoryExists('public\product_images') || Storage::makeDirectory('public\product_images');

        Storage::delete(Storage::allFiles('public\product_images'));

        Product::factory(10)->create();
    }
}
