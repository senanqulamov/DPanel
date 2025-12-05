<?php

namespace App\Livewire\Supplier\Products;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $quantity = 10;

    public ?string $search = null;

    public ?int $marketFilter = null;

    public array $sort = [
        'column' => 'created_at',
        'direction' => 'desc',
    ];

    public array $headers = [
        ['index' => 'id', 'label' => '#'],
        ['index' => 'name', 'label' => 'Name'],
        ['index' => 'sku', 'label' => 'SKU'],
        ['index' => 'category', 'label' => 'Category'],
        ['index' => 'price', 'label' => 'Price'],
        ['index' => 'stock', 'label' => 'Stock'],
        ['index' => 'market', 'label' => 'Market'],
        ['index' => 'action', 'label' => 'Actions', 'sortable' => false],
    ];

    public function render(): View
    {
        return view('livewire.supplier.products.index')
            ->layout('layouts.app');
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        if ($this->quantity == 'all') {
            $this->quantity = Product::count();
        }

        return Product::query()
            ->with('market')
            ->when($this->search !== null, fn (Builder $query) => $query->whereAny(['name', 'sku', 'category'], 'like', '%'.trim($this->search).'%'))
            ->when($this->marketFilter !== null, fn (Builder $query) => $query->where('market_id', $this->marketFilter))
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity)
            ->withQueryString();
    }

    #[Computed]
    public function markets(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Market::orderBy('name')->get();
    }
}
