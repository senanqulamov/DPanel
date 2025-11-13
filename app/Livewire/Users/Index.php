<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function mount(): void
    {
        // Localize headers on mount to ensure they respect current locale
        $this->headers = [
            ['index' => 'id', 'label' => '#'],
            ['index' => 'name', 'label' => __('Name')],
            ['index' => 'email', 'label' => __('E-mail')],
            ['index' => 'created_at', 'label' => __('Created')],
            ['index' => 'action', 'label' => __('Actions'), 'sortable' => false],
        ];
    }

    public bool $slideA = false;

    public $quantity = 5;

    public ?string $search = null;

    public array $sort = [
        'column' => 'created_at',
        'direction' => 'desc',
    ];

    public array $headers = [];

    public function render(): View
    {
        return view('livewire.users.index');
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        if ($this->quantity == 'all') {
            $this->quantity = User::count();
        }

        return User::query()
            ->whereNotIn('id', [Auth::id()])
            ->when($this->search !== null, fn (Builder $query) => $query->whereAny(['name', 'email'], 'like', '%'.trim($this->search).'%'))
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity)
            ->withQueryString();
    }
}
