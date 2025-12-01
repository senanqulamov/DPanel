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

        // Create suppliers (10 users who can supply products)
        $suppliers = User::factory()->supplier()->count(10)->create();

        // Create sellers (5 verified sellers)
        $sellers = User::factory()->seller()->count(5)->create();

        // Create regular buyers (5 users)
        $buyers = User::factory()->buyer()->count(5)->create();

        // Combine all users
        $users = collect([$admin])
            ->merge($suppliers)
            ->merge($sellers)
            ->merge($buyers);

        // Products and Markets
        // Create markets and assign them to sellers
        $markets = collect();
        foreach ($sellers as $seller) {
            // Each seller gets 1-3 markets
            $marketsForSeller = Market::factory(rand(1, 3))->create(['user_id' => $seller->id]);
            $markets = $markets->merge($marketsForSeller);
        }

        // Add some markets for admin as well
        $adminMarkets = Market::factory(2)->create(['user_id' => $admin->id]);
        $markets = $markets->merge($adminMarkets);

        // Ensure products are associated with a market AND a supplier
        $products = Product::factory(40)->make()->each(function (Product $product) use ($markets, $suppliers) {
            $product->market_id = $markets->random()->id;
            $product->supplier_id = $suppliers->random()->id; // Assign random supplier
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

        // RFQ System
        $this->call(RfqSeeder::class);
        $this->command->info('RFQ data seeded successfully!');

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Suppliers: ' . $suppliers->count());
        $this->command->info('Sellers: ' . $sellers->count());
        $this->command->info('Buyers: ' . $buyers->count());

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(TestUserSeeder::class);
    }
}
