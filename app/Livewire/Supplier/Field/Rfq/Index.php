<?php

namespace App\Livewire\Supplier\Field\Rfq;

use App\Models\RfqWorkerAssignment;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public int $perPage = 10;
    public string $search = '';
    public string $statusFilter = '';

    public function render(): View
    {
        $worker = auth()->user();

        $assignments = RfqWorkerAssignment::query()
            ->where('worker_id', $worker->id)
            ->with(['request.buyer', 'request.items', 'assignedBy'])
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $q->whereHas('request', fn ($r) => $r->where('title', 'like', '%' . $this->search . '%'));
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.supplier.field.rfq.index', [
            'assignments' => $assignments,
        ])->layout('layouts.app');
    }
}
