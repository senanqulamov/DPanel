<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $quantity = 5;

    public ?string $search = null;

    public array $sort = [
        'column' => 'created_at',
        'direction' => 'desc',
    ];

    public array $headers = [
        ['index' => 'id', 'label' => '#'],
        ['index' => 'order_number', 'label' => 'Order Number'],
        ['index' => 'product', 'label' => 'Product'],
        ['index' => 'user', 'label' => 'User'],
        ['index' => 'market', 'label' => 'Market'],
        ['index' => 'total', 'label' => 'Total'],
        ['index' => 'status', 'label' => 'Status'],
        ['index' => 'created_at', 'label' => 'Created'],
        ['index' => 'action', 'sortable' => false],
    ];

    public function render(): View
    {
        return view('livewire.orders.index');
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        if ($this->quantity == 'all') {
            $this->quantity = Order::count();
        }

        return Order::query()
            ->with(['product', 'user', 'market'])
            ->when($this->search !== null, fn (Builder $query) => $query->whereAny(['order_number', 'status'], 'like', '%'.trim($this->search).'%'))
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity)
            ->withQueryString();
    }
}
