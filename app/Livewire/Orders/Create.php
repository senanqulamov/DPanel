<?php

namespace App\Livewire\Orders;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Market;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use Alert, WithLogging;

    public Order $order;

    public bool $modal = false;

    /** @var array<int, array{product_id: int|null, quantity: int}> */
    public array $items = [
        ['product_id' => null, 'quantity' => 1],
    ];

    public function mount(): void
    {
        $this->order = new Order;
        $this->order->order_number = 'ORD-' . strtoupper(uniqid());
        $this->order->status = 'processing';
        $this->order->total = 0;
    }

    public function addItem(): void
    {
        $this->items[] = ['product_id' => null, 'quantity' => 1];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function render(): View
    {
        return view('livewire.orders.create', [
            'products' => Product::all(),
            'users' => User::all(),
            'markets' => Market::all(),
        ]);
    }

    public function rules(): array
    {
        return [
            'order.order_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('orders', 'order_number'),
            ],
            'order.user_id' => [
                'required',
                'exists:users,id',
            ],
            'order.market_id' => [
                'nullable',
                'exists:markets,id',
            ],
            'order.status' => [
                'required',
                'string',
                'in:processing,completed,cancelled',
            ],
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','exists:products,id'],
            'items.*.quantity' => ['required','integer','min:1'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->order->total = 0; // will be recalculated
        $this->order->save();

        $total = 0.0;
        $productPrices = Product::whereIn('id', collect($this->items)->pluck('product_id')->filter()->all())
            ->pluck('price', 'id');

        foreach ($this->items as $item) {
            $unit = (float) ($productPrices[$item['product_id']] ?? 0);
            $qty = (int) $item['quantity'];
            $subtotal = round($unit * $qty, 2);
            $this->order->items()->make()->forceFill([
                'product_id' => $item['product_id'],
                'quantity' => $qty,
                'unit_price' => $unit,
                'subtotal' => $subtotal,
            ])->save();
            $total += $subtotal;
        }

        $this->order->forceFill(['total' => $total])->saveQuietly();

        $this->logCreate(Order::class, $this->order->id, [
            'order_number' => $this->order->order_number,
            'total' => $this->order->total,
            'status' => $this->order->status,
        ]);

        $this->dispatch('created');

        $this->reset();
        $this->order = new Order;
        $this->order->order_number = 'ORD-' . strtoupper(uniqid());
        $this->order->status = 'processing';
        $this->order->total = 0;
        $this->items = [['product_id' => null, 'quantity' => 1]];

        $this->success();
    }
}
