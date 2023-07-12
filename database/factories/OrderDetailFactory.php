<?php

namespace Database\Factories;

use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\Order_detail;
use App\Domain\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order_detail>
 */
class OrderDetailFactory extends Factory
{
    protected $model = Order_detail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /**
         * @var Product $product
         */
        $product = Product::all()->random();
        $quantity = $this->faker->numberBetween(1, 10);

        return [
            'order_id' => Order::all()->random()->id,
            'product_id' => $product->id,
            'product_code' => $product->code,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'amount' => $product->price,
            'subtotal' => $product->price * $quantity,
        ];
    }
}
