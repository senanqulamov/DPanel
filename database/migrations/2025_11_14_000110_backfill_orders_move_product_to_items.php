<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::transaction(function () {
            $orders = DB::table('orders')->select('id', 'product_id', 'total')->get();
            if ($orders->isEmpty()) {
                return;
            }

            // Build product prices map to reduce queries
            $productIds = $orders->pluck('product_id')->filter()->unique()->values();
            $prices = DB::table('products')
                ->whereIn('id', $productIds)
                ->pluck('price', 'id');

            foreach ($orders as $order) {
                if (!$order->product_id) {
                    continue;
                }
                $unitPrice = (float) ($prices[$order->product_id] ?? 0);
                if ($unitPrice <= 0) {
                    $unitPrice = (float) $order->total > 0 ? (float) $order->total : 0.0;
                }
                $qty = 1;
                $subtotal = round($unitPrice * $qty, 2);

                // Upsert-like behavior to avoid duplicates on reruns
                $exists = DB::table('order_items')
                    ->where('order_id', $order->id)
                    ->where('product_id', $order->product_id)
                    ->exists();
                if (!$exists) {
                    DB::table('order_items')->insert([
                        'order_id' => $order->id,
                        'product_id' => $order->product_id,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if ((float) $order->total === 0.0) {
                    DB::table('orders')->where('id', $order->id)->update(['total' => $subtotal]);
                }
            }
        });
    }

    public function down(): void
    {
        // No destructive rollback for backfill
    }
};
