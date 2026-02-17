<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition(): array
    {
        // Common attribute names and their possible values
        $attributes = [
            'Color' => ['Red', 'Blue', 'Green', 'Black', 'White', 'Silver', 'Gold', 'Gray', 'Yellow', 'Orange'],
            'Material' => ['Steel', 'Aluminum', 'Plastic', 'Wood', 'Glass', 'Rubber', 'Copper', 'Brass', 'Stainless Steel', 'Carbon Fiber'],
            'Size' => ['Small', 'Medium', 'Large', 'XL', 'XXL', '5x10cm', '10x20cm', '20x30cm'],
            'Weight' => ['100g', '250g', '500g', '1kg', '2kg', '5kg', '10kg', '25kg', '50kg'],
            'Dimensions' => ['10x10x10cm', '20x15x10cm', '30x20x15cm', '50x40x30cm', '100x80x60cm'],
            'Power' => ['110V', '220V', '12V', '24V', '5V', '100W', '250W', '500W', '1000W'],
            'Capacity' => ['100ml', '250ml', '500ml', '1L', '2L', '5L', '10L', '20L', '50L'],
            'Brand' => ['Generic', 'Premium', 'Industrial', 'Professional', 'Standard', 'Economy', 'Elite', 'Pro Series'],
            'Warranty' => ['1 Year', '2 Years', '3 Years', '5 Years', 'Lifetime', '6 Months', '90 Days'],
            'Origin' => ['China', 'Germany', 'USA', 'Japan', 'Italy', 'Taiwan', 'Korea', 'UK', 'France'],
            'Certification' => ['CE', 'ISO 9001', 'RoHS', 'UL Listed', 'FDA Approved', 'EN Standards', 'ANSI Certified'],
            'Temperature Range' => ['-20°C to +60°C', '0°C to +40°C', '-40°C to +85°C', '+5°C to +30°C'],
            'Working Pressure' => ['5 Bar', '10 Bar', '16 Bar', '25 Bar', '100 PSI', '200 PSI', '500 PSI'],
            'Speed' => ['1000 RPM', '1500 RPM', '3000 RPM', '5000 RPM', '10000 RPM'],
            'Voltage' => ['110V', '220V', '230V', '380V', '12V DC', '24V DC', '48V DC'],
            'Thread Size' => ['M6', 'M8', 'M10', 'M12', '1/4"', '1/2"', '3/4"', '1"'],
            'Grade' => ['Grade A', 'Grade B', 'Industrial Grade', 'Commercial Grade', 'Premium Grade'],
            'Finish' => ['Polished', 'Matte', 'Brushed', 'Galvanized', 'Powder Coated', 'Anodized', 'Chrome Plated'],
            'Packaging' => ['Box of 10', 'Box of 50', 'Box of 100', 'Bulk Pack', 'Individual', 'Pack of 5'],
            'Application' => ['General Purpose', 'Heavy Duty', 'Light Duty', 'Industrial Use', 'Commercial Use', 'Residential'],
        ];

        $attributeName = $this->faker->randomElement(array_keys($attributes));
        $attributeValue = $this->faker->randomElement($attributes[$attributeName]);

        return [
            'product_id' => Product::factory(),
            'name' => $attributeName,
            'value' => $attributeValue,
            'sort_order' => 0,
        ];
    }
}
