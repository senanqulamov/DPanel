<?php

namespace App\Livewire\Sla;

use App\Jobs\CheckRfqDeadlines;
use App\Models\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Bus;
use Livewire\Component;

class Index extends Component
{
    public array $overview = [];

    public function mount(): void
    {
        $this->loadOverview();
    }

    public function loadOverview(): void
    {
        $this->overview = [
            'open' => Request::where('status', 'open')->count(),
            'due_7' => Request::where('status', 'open')->whereBetween('deadline', [now(), now()->addDays(7)])->count(),
            'due_3' => Request::where('status', 'open')->whereBetween('deadline', [now(), now()->addDays(3)])->count(),
            'due_1' => Request::where('status', 'open')->whereBetween('deadline', [now(), now()->addDay()])->count(),
            'overdue' => Request::where('status', 'open')->where('deadline', '<', now())->count(),
        ];
    }

    public function dispatchReminders(): void
    {
        Bus::dispatch(new CheckRfqDeadlines());
        $this->dispatchBrowserEvent('toast', ['type' => 'success', 'message' => 'SLA reminders dispatched']);
    }

    public function render(): View
    {
        return view('livewire.sla.index');
    }
}
