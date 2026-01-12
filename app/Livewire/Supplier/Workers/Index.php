<?php

namespace App\Livewire\Supplier\Workers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public function render(): View
    {
        $supplier = Auth::user();

        $workers = User::query()
            ->where('supplier_id', $supplier->id)
            ->whereHas('roles', fn ($q) => $q->where('name', 'supplier_worker'))
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('livewire.supplier.workers.index', [
            'workers' => $workers,
        ]);
    }
}
