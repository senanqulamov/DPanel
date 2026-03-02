<?php

namespace App\Livewire\Supplier\Field;

use App\Models\Log;
use App\Models\RfqWorkerAssignment;
use App\Models\WorkerMessage;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public int $assignedRfqs = 0;
    public int $pendingRfqs = 0;
    public int $inProgressRfqs = 0;
    public int $doneRfqs = 0;
    public int $unreadMessages = 0;
    public int $recentLogs = 0;

    public function mount(): void
    {
        $worker = auth()->user();

        $this->assignedRfqs = RfqWorkerAssignment::where('worker_id', $worker->id)->count();

        $this->pendingRfqs = RfqWorkerAssignment::where('worker_id', $worker->id)
            ->where('status', 'pending')
            ->count();

        $this->inProgressRfqs = RfqWorkerAssignment::where('worker_id', $worker->id)
            ->where('status', 'in_progress')
            ->count();

        $this->doneRfqs = RfqWorkerAssignment::where('worker_id', $worker->id)
            ->where('status', 'done')
            ->count();

        $this->unreadMessages = WorkerMessage::where('receiver_id', $worker->id)
            ->whereNull('read_at')
            ->count();

        $this->recentLogs = Log::where('user_id', $worker->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
    }

    public function render(): View
    {
        return view('livewire.supplier.field.dashboard')
            ->layout('layouts.app');
    }
}
