<?php

namespace App\Livewire\Categories;

use App\Enums\TableHeaders;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Category $category;

    public $quantity = 10;

    public ?string $search = null;

    public array $sort = [
        'column' => 'name',
        'direction' => 'asc',
    ];

    public array $headers = [
        ['index' => 'id', 'label' => '#', 'sortable' => true],
        ['index' => 'name', 'label' => 'Name', 'sortable' => true],
        ['index' => 'sku', 'label' => 'SKU', 'sortable' => true],
        ['index' => 'market', 'label' => 'Market', 'sortable' => false],
        ['index' => 'price', 'label' => 'Price', 'sortable' => true],
        ['index' => 'stock', 'label' => 'Stock', 'sortable' => true],
        ['index' => 'created_at', 'label' => 'Created', 'sortable' => true],
    ];

    public function mount(Category $category): void
    {
        $this->category = $category;
        $this->headers = TableHeaders::make($this->headers);
    }

    public function render(): View
    {
        return view('livewire.categories.show');
    }

    #[Computed]
    public function products()
    {
        if ($this->quantity == 'all') {
            $this->quantity = $this->category->products()->count();
        }

        return $this->category->products()
            ->with(['market', 'supplier'])
            ->when($this->search !== null, fn (Builder $query) =>
                $query->whereAny(['name', 'sku', 'description'], 'like', '%'.trim($this->search).'%')
            )
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity)
            ->withQueryString();
    }
}
