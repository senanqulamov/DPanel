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
use Livewire\Attributes\On;
use Livewire\Component;

class Update extends Component
{
    use Alert, WithLogging;

    public ?Order $order;

    public bool $modal = false;

    public function render(): View
    {
        return view('livewire.orders.update', [
            'products' => Product::all(),
            'users' => User::all(),
            'markets' => Market::all(),
        ]);
    }

    #[On('load::order')]
    public function load(Order $order): void
    {
        $this->order = $order;

        $this->modal = true;
    }

    public function rules(): array
    {
        return [
            'order.order_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('orders', 'order_number')->ignore($this->order->id),
            ],
            'order.product_id' => [
                'required',
                'exists:products,id',
            ],
            'order.user_id' => [
                'required',
                'exists:users,id',
            ],
            'order.market_id' => [
                'nullable',
                'exists:markets,id',
            ],
            'order.total' => [
                'required',
                'numeric',
                'min:0',
            ],
            'order.status' => [
                'required',
                'string',
                'in:processing,completed,cancelled',
            ],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $changes = $this->order->getDirty();
        $this->order->save();

        $this->logUpdate(Order::class, $this->order->id, $changes);

        $this->dispatch('updated');

        $this->resetExcept('order');

        $this->success();
    }
}
