<?php

namespace App\Livewire\Supplier;

use App\Models\Quote;
use App\Models\Request;
use App\Models\SupplierInvitation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public int $pendingInvitations = 0;
    public int $activeQuotes = 0;
    public int $wonQuotes = 0;
    public int $totalRevenue = 0;
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
