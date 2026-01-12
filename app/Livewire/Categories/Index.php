<?php

namespace App\Livewire\Categories;

use App\Enums\TableHeaders;
use App\Models\Category;
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

    public array $sort = [
        'column' => 'name',
        'direction' => 'asc',
    ];

    public array $headers = [
        ['index' => 'id', 'label' => '#', 'sortable' => true],
        ['index' => 'name', 'label' => 'Name', 'sortable' => true],
        ['index' => 'products_count', 'label' => 'Products', 'sortable' => true],
        ['index' => 'created_at', 'label' => 'Created', 'sortable' => true],
        ['index' => 'action', 'label' => '', 'sortable' => false],
    ];

    public function mount(): void
    {
        $this->headers = TableHeaders::make($this->headers);
    }

    public function render(): View
    {
        return view('livewire.categories.index');
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        if ($this->quantity == 'all') {
            $this->quantity = Category::count();
        }

        return Category::query()
            ->withCount('products')
            ->when($this->search !== null, fn (Builder $query) => $query->where('name', 'like', '%'.trim($this->search).'%'))
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity)
            ->withQueryString();
    }
}
