<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => 'password']
        );

        // Additional users
        $users = User::factory(20)->create();
        $users->push($admin);

        // Products and Markets
        $markets = Market::factory(10)->create();

        // Ensure products are associated with a market
        $products = Product::factory(40)->make()->each(function (Product $product) use ($markets) {
            $product->market_id = $markets->random()->id;
            $product->save();
        });

        Order::factory(100)
            ->make()
            ->each(function (Order $order) use ($users, $products) {
                $order->user_id = $users->random()->id;
                $order->save();

                // Attach 1-4 items
                $items = $products->random(rand(1, 4));
                $total = 0;
                foreach ($items as $product) {
                    $qty = rand(1, 5);
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

        // Logs
        Log::factory(120)->create();
    }
}
