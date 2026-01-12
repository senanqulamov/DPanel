<?php

namespace App\Livewire\Reports;

use App\Enums\TableHeaders;
use App\Models\Request;
use App\Models\User;
use App\Services\KpiService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class KpiIndex extends Component
{
    use WithPagination;

    public string $reportType = 'overview';

    public int|string $quantity = 10;

    public ?string $search = null;

    public ?string $statusFilter = null;

    public array $sort = [
        'column' => 'created_at',
        'direction' => 'desc',
    ];

    protected KpiService $kpiService;

    // Headers for different tables
    public array $supplierHeaders = [
        ['index' => 'supplier', 'label' => 'Supplier', 'sortable' => false],
        ['index' => 'invitations', 'label' => 'Invitations', 'sortable' => false],
        ['index' => 'response_rate', 'label' => 'Response Rate', 'sortable' => false],
        ['index' => 'avg_response', 'label' => 'Avg Response Time', 'sortable' => false],
        ['index' => 'quotes', 'label' => 'Quotes', 'sortable' => false],
        ['index' => 'win_rate', 'label' => 'Win Rate', 'sortable' => false],
    ];

    public array $rfqHeaders = [
        ['index' => 'rfq', 'label' => 'RFQ', 'sortable' => false],
        ['index' => 'status', 'label' => 'Status', 'sortable' => false],
        ['index' => 'execution', 'label' => 'Execution Time', 'sortable' => false],
        ['index' => 'first_quote', 'label' => 'Time to First Quote', 'sortable' => false],
        ['index' => 'quotes', 'label' => 'Quotes', 'sortable' => false],
    ];

    public array $topSuppliersHeaders = [
        ['index' => 'rank', 'label' => '#', 'sortable' => false],
        ['index' => 'supplier', 'label' => 'Supplier', 'sortable' => false],
        ['index' => 'response_time', 'label' => 'Avg Response Time', 'sortable' => false],
        ['index' => 'response_rate', 'label' => 'Response Rate', 'sortable' => false],
        ['index' => 'win_rate', 'label' => 'Win Rate', 'sortable' => false],
    ];

    public array $slowestRfqsHeaders = [
        ['index' => 'rfq', 'label' => 'RFQ', 'sortable' => false],
        ['index' => 'status', 'label' => 'Status', 'sortable' => false],
        ['index' => 'execution_time', 'label' => 'Execution Time', 'sortable' => false],
        ['index' => 'quotes', 'label' => 'Quotes', 'sortable' => false],
    ];

    public function boot(KpiService $kpiService): void
    {
        $this->kpiService = $kpiService;
    }

    public function mount(): void
    {
        $this->supplierHeaders = TableHeaders::make($this->supplierHeaders);
        $this->rfqHeaders = TableHeaders::make($this->rfqHeaders);
        $this->topSuppliersHeaders = TableHeaders::make($this->topSuppliersHeaders);
        $this->slowestRfqsHeaders = TableHeaders::make($this->slowestRfqsHeaders);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $data = [
            'aggregate' => $this->kpiService->getAggregateKpis(),
        ];

        return view('livewire.reports.kpi-index', $data);
    }

    #[Computed]
    public function suppliers(): LengthAwarePaginator
    {
        if ($this->quantity == 'all') {
            $this->quantity = User::whereHas('roles', fn($q) => $q->where('name', 'supplier'))
                ->where('supplier_status', 'active')
                ->count();
        }

        $suppliers = User::whereHas('roles', fn($q) => $q->where('name', 'supplier'))
            ->where('supplier_status', 'active')
            ->when($this->search !== null, fn (Builder $query) =>
                $query->whereAny(['name', 'email', 'company_name'], 'like', '%'.trim($this->search).'%')
            )
            ->orderBy('name')
            ->paginate($this->quantity, ['*'], 'suppliers_page')
            ->withQueryString();

        // Transform to include metrics
        $suppliers->getCollection()->transform(function ($supplier) {
            return (object) [
                'supplier' => $supplier,
                'metrics' => $this->kpiService->getSupplierResponseSpeed($supplier),
            ];
        });

        return $suppliers;
    }

    #[Computed]
    public function rfqs(): LengthAwarePaginator
    {
        if ($this->quantity == 'all') {
            $this->quantity = Request::count();
        }

        $rfqs = Request::query()
            ->when($this->search !== null, fn (Builder $query) =>
                $query->whereAny(['title', 'description'], 'like', '%'.trim($this->search).'%')
            )
            ->when($this->statusFilter, fn (Builder $query) =>
                $query->where('status', $this->statusFilter)
            )
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity, ['*'], 'rfqs_page')
            ->withQueryString();

        // Transform to include metrics
        $rfqs->getCollection()->transform(function ($rfq) {
            return (object) $this->kpiService->getRfqExecutionTime($rfq);
        });

        return $rfqs;
    }

    #[Computed]
    public function topSuppliers()
    {
        return $this->kpiService->getTopSuppliersByResponseSpeed(10);
    }

    #[Computed]
    public function slowestRfqs()
    {
        return $this->kpiService->getSlowestRfqs(10);
    }
}
