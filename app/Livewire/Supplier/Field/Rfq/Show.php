<?php

namespace App\Livewire\Supplier\Field\Rfq;

use App\Livewire\Traits\Alert;
use App\Models\Request;
use App\Models\RfqWorkerAssignment;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    use Alert;

    public Request $request;
    public ?RfqWorkerAssignment $assignment = null;

    public function mount(Request $request): void
    {
        $worker = auth()->user();

        // Ensure this worker is actually assigned to this RFQ
        $this->assignment = RfqWorkerAssignment::where('worker_id', $worker->id)
            ->where('request_id', $request->id)
            ->first();

        if (! $this->assignment) {
            abort(403, 'You are not assigned to this RFQ.');
        }

        $this->request = $request->load([
            'buyer',
            'items',
            'latestFieldAssessment',
        ]);
    }

    public function updateStatus(string $status): void
    {
        if (! in_array($status, ['pending', 'in_progress', 'done'])) {
            $this->error(__('Invalid status.'));
            return;
        }

        $this->assignment->update(['status' => $status]);
        $this->assignment->refresh();
        $this->success(__('Status updated to :status.', ['status' => ucfirst(str_replace('_', ' ', $status))]));
    }

    public function render(): View
    {
        return view('livewire.supplier.field.rfq.show')
            ->layout('layouts.app');
    }
}
