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
            [
                'name' => 'Admin User',
                'password' => 'password',
                'is_buyer' => true,
                'is_seller' => true,
                'is_supplier' => true,
            ]
        );

        // Core user segments
        $suppliers = User::factory()->supplier()->count(15)->create();
        $sellers = User::factory()->seller()->count(5)->create();
        $buyers = User::factory()->buyer()->count(15)->create();

        // Combine all users (for generic relationships like logs etc.)
        $users = collect([$admin])
            ->merge($suppliers)
            ->merge($sellers)
            ->merge($buyers);

        // Markets: each seller gets multiple markets; admin has a couple of global ones
        $markets = collect();
        foreach ($sellers as $seller) {
            $marketsForSeller = Market::factory(rand(2, 4))->create(['user_id' => $seller->id]);
            $markets = $markets->merge($marketsForSeller);
        }

        $adminMarkets = Market::factory(2)->create(['user_id' => $admin->id]);
        $markets = $markets->merge($adminMarkets);

        // Products: richer catalogue across markets, each linked to a supplier
        $products = collect();
        foreach ($markets as $market) {
            $count = rand(10, 25);
            $marketProducts = Product::factory($count)->make()->each(function (Product $product) use ($market, $suppliers) {
                $product->market_id = $market->id;
                $product->supplier_id = $suppliers->random()->id;
                $product->save();
            });
            $products = $products->merge($marketProducts);
        }

        // Orders: build realistic orders directly from existing products and markets
        for ($i = 0; $i < 90; $i++) {
            /** @var \App\Models\User $buyer */
            $buyer = $buyers->random();

            /** @var \App\Models\Order $order */
            $order = Order::create([
                'order_number' => 'ORD-' . str_pad((string) ($i + 1), 10, '0', STR_PAD_LEFT),
                'user_id' => $buyer->id,
                'total' => 0,
                'status' => fake()->randomElement(['processing', 'completed', 'cancelled']),
            ]);

            // Attach 1-4 items from existing products
            $lineProducts = $products->random(rand(1, 4));
            $total = 0;

            foreach ($lineProducts as $product) {
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
        }

        // Logs - keep them fairly numerous to exercise log viewers and analytics
        Log::factory(200)->create();

        // RFQ System - relies on already seeded buyers/suppliers/products
        $this->call(RfqSeeder::class);
        $this->command->info('RFQ data seeded successfully!');

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Suppliers: ' . $suppliers->count());
        $this->command->info('Sellers: ' . $sellers->count());
        $this->command->info('Buyers: ' . $buyers->count());

        // Keep roles and permissions seeding untouched
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(TestUserSeeder::class);
    }
}
