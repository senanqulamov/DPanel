<?php

namespace App\Livewire\Orders;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use Alert, WithLogging;

    public Order $order;

    public bool $modal = false;

    /** @var array<int, array{product_id: int|null, market_id: int|null, quantity: int}> */
    public array $items = [];

    /** @var array<int, array{market_id: int|null, product_ids: array<int,int>}> */
    public array $pickers = [];

    public function mount(): void
    {
        $this->order = new Order;
        $this->order->order_number = 'ORD-'.strtoupper(uniqid());
        $this->order->status = 'processing';
        $this->order->total = 0;
        $this->items = [];
        $this->pickers = [];
    }

    public function updated($name, $value): void
    {
        if (preg_match('/^pickers\\.(\d+)\\.market_id$/', (string) $name, $m)) {
            $idx = (int) $m[1];
            if (isset($this->pickers[$idx])) {
                $this->pickers[$idx]['product_ids'] = [];
            }
        }
    }

    public function addPickerLine(): void
    {
        $this->pickers[] = ['market_id' => null, 'product_ids' => []];
    }

    // Remove a picker line
    public function removePickerLine(int $index): void
    {
        unset($this->pickers[$index]);
        $this->pickers = array_values($this->pickers);
    }

    public function addPickerProducts(int $index): void
    {
        $line = $this->pickers[$index] ?? null;
        if (! $line || empty($line['market_id']) || empty($line['product_ids'])) {
            return;
        }

        $marketId = (int) $line['market_id'];
        $validProducts = Product::query()
            ->where('market_id', $marketId)
            ->whereIn('id', $line['product_ids'])
            ->pluck('id')
            ->all();

        foreach ($validProducts as $productId) {
            $existingIndex = collect($this->items)
                ->search(fn ($it) => (int) ($it['product_id'] ?? 0) === (int) $productId);
            if ($existingIndex !== false) {
                $this->items[$existingIndex]['quantity'] = (int) ($this->items[$existingIndex]['quantity'] ?? 0) + 1;
            } else {
                $this->items[] = [
                    'product_id' => (int) $productId,
                    'market_id' => $marketId,
                    'quantity' => 1
                ];
            }
        }

        $this->pickers[$index]['product_ids'] = [];
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
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.market_id' => ['required', 'exists:markets,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
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
                'market_id' => $item['market_id'],
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
        $this->order->order_number = 'ORD-'.strtoupper(uniqid());
        $this->order->status = 'processing';
        $this->order->total = 0;
        $this->items = [];
        $this->pickers = [];

        $this->success();
    }
}
