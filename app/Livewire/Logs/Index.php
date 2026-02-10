<?php

namespace App\Livewire\Logs;

use App\Enums\TableHeaders;
use App\Models\Log;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public int|string $quantity = 10;

    #[Url(as: 'q', except: '')]
    public ?string $search = null;

    #[Url(as: 'type', except: '')]
    public ?string $typeFilter = null;

    #[Locked]
    public array $sort = [
        'column' => 'created_at',
        'direction' => 'desc',
    ];

    #[Locked]
    public array $headers = [];

    /**
     * Type configuration mapping with colors and icons
     */
    private const TYPE_CONFIG = [
        'create'    => ['color' => 'green',  'icon' => 'plus', 'label' => 'Create'],
        'update'    => ['color' => 'blue',   'icon' => 'pencil', 'label' => 'Update'],
        'delete'    => ['color' => 'red',    'icon' => 'trash', 'label' => 'Delete'],
        'page_view' => ['color' => 'purple', 'icon' => 'eye', 'label' => 'Page View'],
        'auth'      => ['color' => 'yellow', 'icon' => 'lock-closed', 'label' => 'Auth'],
        'error'     => ['color' => 'red',    'icon' => 'exclamation-triangle', 'label' => 'Error'],
        'export'    => ['color' => 'indigo', 'icon' => 'arrow-down-tray', 'label' => 'Export'],
        'import'    => ['color' => 'indigo', 'icon' => 'arrow-up-tray', 'label' => 'Import'],
        'bulk'      => ['color' => 'orange', 'icon' => 'square-3-stack-3d', 'label' => 'Bulk'],
        'system'    => ['color' => 'slate',  'icon' => 'cog', 'label' => 'System'],
        'security'  => ['color' => 'pink',   'icon' => 'shield-check', 'label' => 'Security'],
        'config'    => ['color' => 'cyan',   'icon' => 'wrench', 'label' => 'Config'],
    ];

    private const DEFAULT_TYPE_CONFIG = [
        'color' => 'gray',
        'icon' => 'information-circle',
        'label' => 'Info'
    ];

    /**
     * Mount the component and initialize headers
     */
    public function mount(): void
    {
        $this->headers = TableHeaders::make([
            ['index' => 'id', 'label' => '#'],
            ['index' => 'type', 'label' => 'Type'],
            ['index' => 'action', 'label' => 'Action'],
            ['index' => 'message', 'label' => 'Message'],
            ['index' => 'user_id', 'label' => 'User'],
            ['index' => 'ip_address', 'label' => 'IP Address'],
            ['index' => 'created_at', 'label' => 'Created'],
            ['index' => 'action_column', 'label' => 'Actions', 'sortable' => false],
        ]);
    }

    /**
     * Render the component view
     */
    public function render(): View
    {
        return view('livewire.logs.index');
    }

    /**
     * Get paginated and filtered logs with optimized queries
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        try {
            $perPage = $this->getPerPageCount();

            return $this->buildQuery()
                ->paginate($perPage)
                ->withQueryString();
        } catch (\Exception $e) {
            // Log error but don't crash the page
            logger()->error('Error fetching logs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return empty paginator
            return new LengthAwarePaginator([], 0, 10);
        }
    }

    /**
     * Get available log types with caching
     */
    #[Computed]
    public function logTypes(): array
    {
        try {
            return Cache::remember('log_types_list', 3600, function () {
                return Log::query()
                    ->select('type')
                    ->distinct()
                    ->whereNotNull('type')
                    ->orderBy('type')
                    ->pluck('type')
                    ->filter()
                    ->values()
                    ->toArray();
            });
        } catch (\Exception $e) {
            logger()->error('Error fetching log types', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get formatted log type options for select dropdown
     */
    #[Computed]
    public function logTypeOptions(): array
    {
        try {
            return collect($this->logTypes)
                ->map(function($type) {
                    return [
                        'label' => $this->formatTypeLabel($type),
                        'value' => $type
                    ];
                })
                ->values()
                ->toArray();
        } catch (\Exception $e) {
            logger()->error('Error formatting log type options', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Format type label for display
     */
    private function formatTypeLabel(string $type): string
    {
        return ucfirst(str_replace('_', ' ', $type));
    }

    /**
     * Build the optimized query for logs
     */
    private function buildQuery(): Builder
    {
        return Log::query()
            ->select([
                'logs.id',
                'logs.type',
                'logs.action',
                'logs.message',
                'logs.user_id',
                'logs.ip_address',
                'logs.created_at',
            ])
            ->with(['user:id,name'])
            ->when($this->hasSearchTerm(), fn(Builder $query) => $this->applySearch($query))
            ->when($this->hasTypeFilter(), fn(Builder $query) => $query->where('type', $this->typeFilter))
            ->orderBy($this->sort['column'], $this->sort['direction']);
    }

    /**
     * Apply search filters to the query
     */
    private function applySearch(Builder $query): Builder
    {
        $searchTerm = '%' . trim($this->search) . '%';

        return $query->where(function (Builder $q) use ($searchTerm) {
            $q->where('type', 'like', $searchTerm)
                ->orWhere('action', 'like', $searchTerm)
                ->orWhere('message', 'like', $searchTerm)
                ->orWhere('ip_address', 'like', $searchTerm)
                ->orWhereHas('user', fn(Builder $userQuery) =>
                $userQuery->where('name', 'like', $searchTerm)
                );
        });
    }

    /**
     * Get per page count, handling 'all' case efficiently
     */
    private function getPerPageCount(): int
    {
        if ($this->quantity === 'all') {
            return Cache::remember('logs_total_count', 300, fn() => Log::count());
        }

        return (int) $this->quantity;
    }

    /**
     * Check if search term exists
     */
    private function hasSearchTerm(): bool
    {
        return !empty($this->search) && trim($this->search) !== '';
    }

    /**
     * Check if type filter is applied
     */
    private function hasTypeFilter(): bool
    {
        return !empty($this->typeFilter) && trim($this->typeFilter) !== '';
    }

    /**
     * Get type configuration for display - ERROR PROOF VERSION
     */
    public function getTypeConfig(?string $type): array
    {
        // Handle null or empty type
        if (empty($type)) {
            $type = 'default';
        }

        // Get config or use default
        $config = self::TYPE_CONFIG[$type] ?? self::DEFAULT_TYPE_CONFIG;

        // Ensure all required keys exist
        $color = $config['color'] ?? 'gray';
        $icon = $config['icon'] ?? 'information-circle';
        $label = $config['label'] ?? 'Info';

        return [
            'color' => $color,
            'icon' => $icon,
            'label' => $label,
            'badge_class' => "bg-{$color}-100 dark:bg-{$color}-900",
            'icon_class' => "text-{$color}-600 dark:text-{$color}-400",
        ];
    }

    /**
     * Get statistics for the logs
     */
    #[Computed]
    public function statistics(): array
    {
        try {
            return Cache::remember('logs_statistics', 600, function () {
                $stats = DB::table('logs')
                    ->select('type', DB::raw('count(*) as count'))
                    ->groupBy('type')
                    ->get()
                    ->mapWithKeys(fn($item) => [$item->type => $item->count])
                    ->toArray();

                return [
                    'total' => array_sum($stats),
                    'by_type' => $stats,
                    'today' => Log::whereDate('created_at', today())->count(),
                    'this_week' => Log::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                ];
            });
        } catch (\Exception $e) {
            logger()->error('Error fetching statistics', ['error' => $e->getMessage()]);
            return [
                'total' => 0,
                'by_type' => [],
                'today' => 0,
                'this_week' => 0,
            ];
        }
    }

    /**
     * Clear the type filter and reset pagination
     */
    public function clearTypeFilter(): void
    {
        $this->typeFilter = null;
        $this->resetPage();
        $this->clearComputedProperties();
    }

    /**
     * Clear all filters
     */
    public function clearAllFilters(): void
    {
        $this->typeFilter = null;
        $this->search = null;
        $this->resetPage();
        $this->clearComputedProperties();
    }

    /**
     * Handle type filter updates
     */
    public function updatedTypeFilter(): void
    {
        $this->resetPage();
        $this->clearComputedProperties();
    }

    /**
     * Handle search updates
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->clearComputedProperties();
    }

    /**
     * Handle quantity/per-page updates
     */
    public function updatedQuantity(): void
    {
        $this->resetPage();
        $this->clearComputedProperties();
    }

    /**
     * View a specific log entry
     */
    public function viewLog(int $logId): void
    {
        try {
            $this->dispatch('load::log', log: $logId)
                ->to('logs.log-view');
        } catch (\Exception $e) {
            logger()->error('Error dispatching viewLog event', [
                'log_id' => $logId,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error loading log details'
            ]);
        }
    }

    /**
     * Delete a log entry
     */
    public function deleteLog(int $logId): void
    {
        try {
            $log = Log::findOrFail($logId);
            $log->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Log deleted successfully'
            ]);

            $this->refreshLogs();
        } catch (\Exception $e) {
            logger()->error('Error deleting log', [
                'log_id' => $logId,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error deleting log'
            ]);
        }
    }

    /**
     * Refresh logs when data is updated
     */
    #[On('log-created')]
    #[On('log-updated')]
    #[On('log-deleted')]
    public function refreshLogs(): void
    {
        try {
            // Clear all caches
            Cache::forget('log_types_list');
            Cache::forget('logs_total_count');
            Cache::forget('logs_statistics');

            // Reset pagination and computed properties
            $this->resetPage();
            $this->clearComputedProperties();
        } catch (\Exception $e) {
            logger()->error('Error refreshing logs', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Clear computed properties to force refresh
     */
    private function clearComputedProperties(): void
    {
        unset($this->rows, $this->logTypes, $this->logTypeOptions, $this->statistics);
    }

    /**
     * Export logs to CSV
     */
    public function exportLogs(): void
    {
        try {
            $this->dispatch('export-logs-started');
            // Implementation would go here
        } catch (\Exception $e) {
            logger()->error('Error exporting logs', ['error' => $e->getMessage()]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error exporting logs'
            ]);
        }
    }
}
