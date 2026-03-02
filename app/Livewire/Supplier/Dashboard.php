<?php

namespace App\Livewire\Supplier;

use App\Models\Quote;
use App\Models\Request;
use App\Models\RfqWorkerAssignment;
use App\Models\SupplierInvitation;
use App\Models\User;
use App\Models\WorkerMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public int $pendingInvitations = 0;
    public int $activeQuotes = 0;
    public int $wonQuotes = 0;
    public int $totalRevenue = 0;
    public int $totalWorkers = 0;
    public int $activeWorkers = 0;
    public int $workerAssignments = 0;
    public int $unreadWorkerMessages = 0;
    public Collection $recentInvitations;
    public Collection $rfqsWithFieldAssessment;

    public function mount(): void
    {
        $supplier = auth()->user();

        // Get statistics
        $this->pendingInvitations = SupplierInvitation::query()
            ->where('supplier_id', $supplier->id)
            ->where('status', 'pending')
            ->count();

        $this->activeQuotes = Quote::query()
            ->where('supplier_id', $supplier->id)
            ->whereIn('status', ['draft', 'submitted'])
            ->count();

        $this->wonQuotes = Quote::query()
            ->where('supplier_id', $supplier->id)
            ->where('status', 'won')
            ->count();

        $this->totalRevenue = Quote::query()
            ->where('supplier_id', $supplier->id)
            ->where('status', 'won')
            ->sum('total_amount');

        // Worker stats
        $this->totalWorkers = User::where('supplier_id', $supplier->id)
            ->supplierWorkers()
            ->count();

        $this->activeWorkers = User::where('supplier_id', $supplier->id)
            ->supplierWorkers()
            ->where('is_active', true)
            ->count();

        $this->workerAssignments = RfqWorkerAssignment::where('assigned_by', $supplier->id)->count();

        $this->unreadWorkerMessages = WorkerMessage::where('receiver_id', $supplier->id)
            ->whereNull('read_at')
            ->count();

        // Get recent invitations with request details
        $this->recentInvitations = SupplierInvitation::query()
            ->where('supplier_id', $supplier->id)
            ->where('status', 'pending')
            ->with(['request' => function ($query) {
                $query->with(['buyer', 'latestFieldAssessment']);
            }])
            ->latest()
            ->take(5)
            ->get();

        // Get RFQs with completed field assessments (relevant to supplier)
        $this->rfqsWithFieldAssessment = Request::query()
            ->whereHas('supplierInvitations', function ($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->where('requires_field_assessment', true)
            ->where('field_assessment_status', 'completed')
            ->whereDoesntHave('quotes', function ($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->with(['buyer', 'latestFieldAssessment'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function render(): View
    {
        return view('livewire.supplier.dashboard')
            ->layout('layouts.app');
    }
}
