<?php

namespace Database\Factories;

use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\Order_detail;
use App\Domain\Payments\Enums\PaymentMethod;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'ORD-'.$this->faker->unique()->randomNumber(6),
            'user_id' => User::all()->random()->id,
            'status' => $this->faker->randomElement(array_column(OrderStatus::cases(), 'value')),
            'payment_method' => $this->faker->randomElement(array_column(PaymentMethod::cases(), 'value')),
            'active' => false,
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($order) {
            Order_detail::factory()->count(rand(1, 3))->create(['order_id' => $order->id, 'created_at' => $order->created_at])->each(function ($order_detail) use ($order) {
                $order->total += $order_detail->subtotal;
            });
            $order->save();
        });
    }
}
