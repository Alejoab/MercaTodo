<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        File::delete(File::allFiles(storage_path('app/public/product_images')));

        Product::factory(env('PRODUCT_SEEDER'))->create();
    }
}
