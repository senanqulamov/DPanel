<?php

namespace App\Livewire\Supplier\Orders;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Market;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    use Alert, WithLogging;

    public ?int $selectedMarketId = null;
    public array $cart = [];
    public ?string $notes = null;

    public function mount(): void
    {
        // Initialize empty cart
    }

    public function getMarketsProperty()
    {
        return Market::with(['seller', 'products'])
            ->whereHas('products', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->get();
    }

    public function getSelectedMarketProperty()
    {
        if (!$this->selectedMarketId) {
            return null;
        }
        return Market::with(['seller', 'products' => function ($query) {
            $query->where('stock', '>', 0);
        }])->find($this->selectedMarketId);
    }

    public function selectMarket(int $marketId): void
    {
        $this->selectedMarketId = $marketId;
        $this->cart = []; // Clear cart when changing market
    }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);

        if (!$product || $product->stock < 1) {
            $this->error(__('Product is out of stock'));
            return;
        }

        if (!isset($this->cart[$productId])) {
            $this->cart[$productId] = [
                'product_id' => $productId,
                'quantity' => 1,
                'unit_price' => $product->price,
                'name' => $product->name,
                'max_stock' => $product->stock,
            ];
        } else {
            if ($this->cart[$productId]['quantity'] < $product->stock) {
                $this->cart[$productId]['quantity']++;
            } else {
                $this->warning(__('Maximum stock reached'));
                return;
            }
        }

        $this->success(__('Product added to cart'));
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        if (!isset($this->cart[$productId])) {
            return;
        }

        $product = Product::find($productId);

        if ($quantity < 1) {
            $this->removeFromCart($productId);
            return;
        }

        if ($quantity > $product->stock) {
            $this->warning(__('Quantity exceeds available stock'));
            $this->cart[$productId]['quantity'] = $product->stock;
            return;
        }

        $this->cart[$productId]['quantity'] = $quantity;
    }

    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
        $this->success(__('Product removed from cart'));
    }

    public function getTotalProperty(): float
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['unit_price'] * $item['quantity'];
        }
        return $total;
    }

    public function placeOrder()
    {
        if (empty($this->cart)) {
            $this->error(__('Cart is empty'));
            return;
        }

        if (!$this->selectedMarketId) {
            $this->error(__('Please select a market'));
            return;
        }

        $market = Market::find($this->selectedMarketId);
        if (!$market) {
            $this->error(__('Market not found'));
            return;
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'seller_id' => $market->user_id,
                'total' => $this->total,
                'status' => Order::STATUS_PENDING,
                'notes' => $this->notes,
            ]);

            // Create order items
            foreach ($this->cart as $item) {
                $product = Product::find($item['product_id']);

                // Check stock again
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Insufficient stock for ' . $product->name);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'market_id' => $this->selectedMarketId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['unit_price'] * $item['quantity'],
                ]);

                // Reduce stock
                $product->decrement('stock', $item['quantity']);
            }

            $this->logCreate(Order::class, $order->id, [
                'order_number' => $order->order_number,
                'total' => $order->total,
                'items_count' => count($this->cart),
                'market_id' => $this->selectedMarketId,
            ]);

            DB::commit();

            $this->success(__('Order placed successfully! Order number: :number', ['number' => $order->order_number]));

            // Reset form
            $this->cart = [];
            $this->notes = null;
            $this->selectedMarketId = null;

            // Redirect to orders list
            return $this->redirect(route('supplier.orders.index'));

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error(__('Failed to place order: :error', ['error' => $e->getMessage()]));
        }
    }

    public function render(): View
    {
        return view('livewire.supplier.orders.create')
            ->layout('layouts.app');
    }
}
