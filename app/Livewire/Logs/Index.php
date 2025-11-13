<?php

namespace App\Livewire\Logs;

use App\Livewire\Traits\Alert;
use App\Models\Log;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, Alert;

    public $quantity = 10;

    public ?string $search = null;

    public ?string $typeFilter = null;

    public ?string $userFilter = null;

    public ?string $actionFilter = null;

    public ?string $modelFilter = null;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public bool $showFilters = false;

    public ?int $selectedLogId = null;

    public bool $showModal = false;

    public ?int $deleteLogId = null;

    public array $sort = [
        'column' => 'created_at',
        'direction' => 'desc',
    ];

    public array $headers = [
        ['index' => 'id', 'label' => '#', 'sortable' => true],
        ['index' => 'type', 'label' => 'Type', 'sortable' => true],
        ['index' => 'user_id', 'label' => 'User', 'sortable' => true],
        ['index' => 'model', 'label' => 'Model', 'sortable' => true],
        ['index' => 'action', 'label' => 'Action', 'sortable' => true],
        ['index' => 'message', 'label' => 'Message', 'sortable' => false],
        ['index' => 'ip_address', 'label' => 'IP', 'sortable' => false],
        ['index' => 'created_at', 'label' => 'Created', 'sortable' => true],
        ['index' => 'actions', 'label' => '', 'sortable' => false],
    ];

    public function mount(): void
    {
        // Set default date range to last 7 days
        $this->dateTo = now()->format('Y-m-d');
        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
    }

    public function render(): View
    {
        return view('livewire.logs.index');
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        if ($this->quantity == 'all') {
            $this->quantity = Log::count();
        }

        return Log::query()
            ->with('user:id,name,email')
            ->when($this->search !== null, fn (Builder $query) => $query->whereAny(['type', 'message', 'action', 'model'], 'like', '%'.trim($this->search).'%'))
            ->when($this->typeFilter !== null, fn (Builder $query) => $query->where('type', $this->typeFilter))
            ->when($this->userFilter !== null, fn (Builder $query) => $query->where('user_id', $this->userFilter))
            ->when($this->actionFilter !== null, fn (Builder $query) => $query->where('action', 'like', '%'.$this->actionFilter.'%'))
            ->when($this->modelFilter !== null, fn (Builder $query) => $query->where('model', $this->modelFilter))
            ->when($this->dateFrom !== null, fn (Builder $query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo !== null, fn (Builder $query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity)
            ->withQueryString();
    }

    #[Computed]
    public function logTypes(): array
    {
        return Log::select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type', 'type')
            ->toArray();
    }

    #[Computed]
    public function users(): array
    {
        return User::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    #[Computed]
    public function models(): array
    {
        return Log::select('model')
            ->distinct()
            ->whereNotNull('model')
            ->orderBy('model')
            ->pluck('model', 'model')
            ->toArray();
    }

    #[Computed]
    public function actions(): array
    {
        return Log::select('action')
            ->distinct()
            ->whereNotNull('action')
            ->orderBy('action')
            ->pluck('action', 'action')
            ->toArray();
    }

    public function clearFilters(): void
    {
        $this->reset(['typeFilter', 'userFilter', 'actionFilter', 'modelFilter', 'search']);
        $this->dateTo = now()->format('Y-m-d');
        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->resetPage();
    }

    public function toggleFilters(): void
    {
        $this->showFilters = ! $this->showFilters;
    }

    public function viewLog(int $logId): void
    {
        $this->selectedLogId = $logId;
        $this->showModal = true;
    }

    #[Computed]
    public function selectedLog(): ?Log
    {
        return $this->selectedLogId ? Log::with('user')->find($this->selectedLogId) : null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedLogId = null;
    }

    public function exportLogs(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Get filtered logs
        $logs = Log::query()
            ->with('user:id,name,email')
            ->when($this->search !== null, fn (Builder $query) => $query->whereAny(['type', 'message', 'action', 'model'], 'like', '%'.trim($this->search).'%'))
            ->when($this->typeFilter !== null, fn (Builder $query) => $query->where('type', $this->typeFilter))
            ->when($this->userFilter !== null, fn (Builder $query) => $query->where('user_id', $this->userFilter))
            ->when($this->actionFilter !== null, fn (Builder $query) => $query->where('action', 'like', '%'.$this->actionFilter.'%'))
            ->when($this->modelFilter !== null, fn (Builder $query) => $query->where('model', $this->modelFilter))
            ->when($this->dateFrom !== null, fn (Builder $query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo !== null, fn (Builder $query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy(...array_values($this->sort))
            ->limit(10000) // Limit to prevent memory issues
            ->get();

        // Create CSV content
        $csv = "ID,Type,User,Action,Model,Model ID,Message,IP Address,Created At\n";

        foreach ($logs as $log) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s","%s"'."\n",
                $log->id,
                $log->type,
                $log->user ? $log->user->name : 'System',
                $log->action ?? '',
                $log->model ?? '',
                $log->model_id ?? '',
                str_replace('"', '""', $log->message),
                $log->ip_address ?? '',
                $log->created_at->format('Y-m-d H:i:s')
            );
        }

        // Return download response
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'logs-export-'.now()->format('Y-m-d-His').'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    #[Renderless]
    public function confirmDelete(int $logId): void
    {
        $this->deleteLogId = $logId;

        $this->question()
            ->confirm(method: 'deleteLog')
            ->cancel()
            ->send();
    }

    public function deleteLog(): void
    {
        if ($this->deleteLogId) {
            $log = Log::find($this->deleteLogId);

            if ($log) {
                $log->delete();
                $this->success('Log deleted successfully');
            }

            $this->deleteLogId = null;
            $this->resetPage();
        }
    }
}
