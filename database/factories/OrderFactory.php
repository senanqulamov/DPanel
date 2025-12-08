<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $statusPool = ['processing', 'completed', 'cancelled'];

        return [
            'order_number' => 'ORD-'.fake()->unique()->numerify('##########'),
            'total' => 0,
            'status' => fake()->randomElement($statusPool),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::inRandomOrder()->take(fake()->numberBetween(1, 4))->get();
            $total = 0;
            foreach ($products as $product) {
                $qty = fake()->numberBetween(1, 5);
                $unit = (float) $product->price;
                $subtotal = round($qty * $unit, 2);
                $order->items()->create([
                    'product_id' => $product->id,
                    'market_id' => $product->market_id,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }
            $order->forceFill(['total' => $total])->saveQuietly();
        });
    }
}
