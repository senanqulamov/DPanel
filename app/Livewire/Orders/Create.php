<?php

namespace App\Livewire\Orders;

use App\Livewire\Traits\Alert;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Market;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use Alert;

    public Order $order;

    public bool $modal = false;

    public function mount(): void
    {
        $this->order = new Order;
        $this->order->order_number = 'ORD-' . strtoupper(uniqid());
        $this->order->status = 'processing';
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

        $this->order->save();

        $this->dispatch('created');

        $this->reset();
        $this->order = new Order;
        $this->order->order_number = 'ORD-' . strtoupper(uniqid());
        $this->order->status = 'processing';

        $this->success();
    }
}
