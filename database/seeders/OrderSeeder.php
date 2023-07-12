<?php

namespace Database\Seeders;

use App\Domain\Orders\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()->count(config('seeders.orders'))->create();
    }
}
