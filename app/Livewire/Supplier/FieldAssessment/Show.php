<?php

namespace App\Livewire\Supplier\FieldAssessment;

use App\Models\FieldAssessment;
use App\Models\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Request $request;
    public ?FieldAssessment $fieldAssessment = null;

    public function mount(Request $request): void
    {
        $user = Auth::user();

        $this->request = $request->load(['buyer', 'items']);

        // Get supplier's field assessment
        $this->fieldAssessment = FieldAssessment::where('request_id', $request->id)
            ->where('supplier_id', $user->id)
            ->first();

        if (!$this->fieldAssessment) {
            redirect()->route('supplier.rfq.show', $request);
        }
    }

    public function render(): View
    {
        return view('livewire.supplier.field-assessment.show');
    }
}
