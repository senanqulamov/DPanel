<?php

namespace App\Livewire\Supplier\Workers;

use App\Livewire\Traits\Alert;
use App\Models\Log;
use App\Models\Request;
use App\Models\RfqWorkerAssignment;
use App\Models\User;
use App\Models\WorkerMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use Alert, WithPagination;

    public User $worker;

    public string $activeTab = 'rfqs';

    // Messaging
    public string $newMessage = '';

    // RFQ assignment
    public string $rfqSearch = '';
    public array $assignmentStatuses = [];

    // Logs filters
    public ?string $logSearch = null;
    public ?string $logTypeFilter = null;

    public function mount(User $worker): void
    {
        $supplier = Auth::user();

        // Ensure this worker belongs to this supplier
        if ((int) $worker->supplier_id !== (int) $supplier->id) {
            abort(403, 'This worker does not belong to your account.');
        }

        $this->worker = $worker;

        // Mark incoming messages from worker as read
        WorkerMessage::where('sender_id', $worker->id)
            ->where('receiver_id', $supplier->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage('available_page');
        $this->resetPage('assigned_page');
    }

    public function updatedRfqSearch(): void
    {
        $this->resetPage('available_page');
    }

    // ---- RFQ Assignment ----

    private function getAvailableRfqs()
    {
        return Request::query()
            ->with(['buyer'])
            ->withCount(['items'])
            ->where('status', 'open')
            ->whereDoesntHave('workerAssignments', fn ($q) => $q->where('worker_id', $this->worker->id))
            ->when($this->rfqSearch, fn ($q) =>
                $q->where('title', 'like', '%' . $this->rfqSearch . '%')
            )
            ->latest()
            ->paginate(10, ['*'], 'available_page');
    }

    private function getWorkerAssignments()
    {
        return RfqWorkerAssignment::query()
            ->where('worker_id', $this->worker->id)
            ->with(['request.buyer', 'request.items'])
            ->latest()
            ->paginate(10, ['*'], 'assigned_page');
    }

    public function confirmAssignRfq(int $requestId): void
    {
        $this->question(__('Assign this RFQ to the worker?'), __('Confirm Assignment'))
            ->confirm(method: 'assignRfq', params: ['requestId' => $requestId])
            ->cancel()
            ->send();
    }

    public function assignRfq($params): void
    {
        $requestId = is_array($params) && isset($params['requestId']) ? (int) $params['requestId'] : (int) $params;

        $supplier = Auth::user();

        $request = Request::where('status', 'open')->findOrFail($requestId);

        if (RfqWorkerAssignment::where('worker_id', $this->worker->id)->where('request_id', $request->id)->exists()) {
            $this->error(__('Worker is already assigned to this RFQ.'));
            return;
        }

        RfqWorkerAssignment::create([
            'request_id'  => $request->id,
            'worker_id'   => $this->worker->id,
            'assigned_by' => $supplier->id,
            'status'      => 'pending',
            'assigned_at' => now(),
        ]);

        $this->success(__('RFQ assigned to worker successfully.'));
    }

    public function confirmUnassignRfq(int $assignmentId): void
    {
        $this->question(__('Remove this RFQ assignment from the worker?'), __('Confirm Removal'))
            ->confirm(method: 'unassignRfq', params: ['assignmentId' => $assignmentId])
            ->cancel()
            ->send();
    }

    public function unassignRfq($params): void
    {
        $assignmentId = is_array($params) && isset($params['assignmentId']) ? (int) $params['assignmentId'] : (int) $params;

        $assignment = RfqWorkerAssignment::where('worker_id', $this->worker->id)
            ->findOrFail($assignmentId);

        $assignment->delete();

        $this->success(__('RFQ assignment removed.'));
    }

    public function updatedAssignmentStatuses(string $value, string $key): void
    {
        $assignmentId = (int) $key;

        if (! in_array($value, ['pending', 'in_progress', 'done'])) {
            $this->error(__('Invalid status.'));
            return;
        }

        $assignment = RfqWorkerAssignment::where('worker_id', $this->worker->id)
            ->findOrFail($assignmentId);

        $assignment->update(['status' => $value]);

        $this->success(__('Status updated.'));
    }

    // ---- Messaging ----

    #[Computed]
    public function messages()
    {
        $supplier = Auth::user();

        return WorkerMessage::query()
            ->conversation($supplier->id, $this->worker->id)
            ->with(['sender', 'rfq'])
            ->orderBy('created_at')
            ->get();
    }

    public function sendMessage(): void
    {
        $validator = Validator::make(
            ['newMessage' => $this->newMessage],
            ['newMessage' => ['required', 'string', 'max:2000']]
        );

        if ($validator->fails()) {
            $this->addError('newMessage', $validator->errors()->first('newMessage'));
            return;
        }

        $supplier = Auth::user();

        WorkerMessage::create([
            'sender_id'   => $supplier->id,
            'receiver_id' => $this->worker->id,
            'body'        => $this->newMessage,
        ]);

        $this->reset('newMessage');
        unset($this->messages);
        $this->dispatch('message-sent');
    }

    // ---- Logs ----

    #[Computed]
    public function workerLogs()
    {
        return Log::query()
            ->where('user_id', $this->worker->id)
            ->when($this->logSearch !== null && $this->logSearch !== '', function ($q) {
                $term = '%' . trim($this->logSearch) . '%';
                $q->whereAny(['type', 'action', 'message'], 'like', $term);
            })
            ->when($this->logTypeFilter !== null && $this->logTypeFilter !== '', fn ($q) => $q->where('type', $this->logTypeFilter))
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    #[Computed]
    public function logTypes(): array
    {
        return Log::query()
            ->where('user_id', $this->worker->id)
            ->select('type')
            ->distinct()
            ->pluck('type')
            ->toArray();
    }

    #[Computed]
    public function logStats(): array
    {
        $base = Log::query()->where('user_id', $this->worker->id);

        return [
            'total'  => (clone $base)->count(),
            'today'  => (clone $base)->whereDate('created_at', today())->count(),
            'week'   => (clone $base)->where('created_at', '>=', now()->subDays(7))->count(),
            'errors' => (clone $base)->where('type', 'error')->count(),
        ];
    }

    public function clearLogFilters(): void
    {
        $this->logSearch = null;
        $this->logTypeFilter = null;
        $this->resetPage();
        unset($this->workerLogs);
    }

    public function updatedLogSearch(): void
    {
        $this->resetPage();
        unset($this->workerLogs);
    }

    public function updatedLogTypeFilter(): void
    {
        $this->resetPage();
        unset($this->workerLogs);
    }

    public function render(): View
    {
        $workerAssignments = $this->getWorkerAssignments();

        // Populate the statuses array so wire:model.live has initial values
        foreach ($workerAssignments as $assignment) {
            if (! isset($this->assignmentStatuses[$assignment->id])) {
                $this->assignmentStatuses[$assignment->id] = $assignment->status;
            }
        }

        return view('livewire.supplier.workers.show', [
            'availableRfqs'     => $this->getAvailableRfqs(),
            'workerAssignments' => $workerAssignments,
        ])->layout('layouts.app');
    }
}
