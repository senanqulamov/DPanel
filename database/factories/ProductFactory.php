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
        $categories = [
            'Office Supplies',
            'Industrial Components',
            'IT Hardware',
            'Packaging',
            'Maintenance',
        ];

        $category = $this->faker->randomElement($categories);

        $baseName = match ($category) {
            'Office Supplies' => $this->faker->randomElement([
                'A4 Copy Paper 80gsm',
                'Ballpoint Pens Blue (Pack of 50)',
                'Office Desk Chair Ergonomic',
                'Whiteboard Markers Assorted',
            ]),
            'Industrial Components' => $this->faker->randomElement([
                'M8 Hex Bolt Stainless Steel',
                'Hydraulic Hose 1/2\"',
                'Industrial Safety Gloves Nitrile',
                'PPE Safety Helmet EN397',
            ]),
            'IT Hardware' => $this->faker->randomElement([
                '24\" LED Monitor Full HD',
                'Business Laptop 16GB RAM',
                'Network Switch 24 Port Gigabit',
                'External SSD 1TB USB-C',
            ]),
            'Packaging' => $this->faker->randomElement([
                'Cardboard Box 600x400x400mm',
                'Stretch Film Roll 20mic',
                'Packing Tape 48mm x 66m',
                'Bubble Wrap Roll 100m',
            ]),
            'Maintenance' => $this->faker->randomElement([
                'Multi-Purpose Cleaner 5L',
                'Industrial Degreaser 25L',
                'Floor Cleaning Pads 17\"',
                'Hand Soap Dispenser 1L',
            ]),
            default => $this->faker->words(3, true),
        };

        // Price distribution: many cheaper items, some expensive
        $priceTier = $this->faker->numberBetween(1, 10);
        if ($priceTier <= 6) {
            $price = $this->faker->randomFloat(2, 5, 100); // 60%
        } elseif ($priceTier <= 9) {
            $price = $this->faker->randomFloat(2, 100, 500); // 30%
        } else {
            $price = $this->faker->randomFloat(2, 500, 5000); // 10%
        }

        // Stock distribution skewed towards 10-200
        $stockTier = $this->faker->numberBetween(1, 10);
        if ($stockTier <= 7) {
            $stock = $this->faker->numberBetween(10, 200);
        } elseif ($stockTier <= 9) {
            $stock = $this->faker->numberBetween(200, 1000);
        } else {
            $stock = $this->faker->numberBetween(0, 20);
        }

        return [
            'name' => $baseName,
            'sku' => strtoupper($this->faker->unique()->bothify('PRD-####-????')),
            'price' => $price,
            'stock' => $stock,
            'category' => $category,
            'market_id' => Market::factory(),
        ];
    }
}
