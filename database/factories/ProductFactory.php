<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Market;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'sku' => strtoupper($this->faker->unique()->bothify('PRD-########-??')),
            'price' => $this->faker->randomFloat(2, 10, 2000),
            'stock' => $this->faker->numberBetween(0, 500),
            'category' => $this->faker->randomElement(['Electronics', 'Wearables', 'Computers', 'Audio', 'Accessories']),
            'market_id' => Market::factory(),
        ];
    }
}
