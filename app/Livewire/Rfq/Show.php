<?php

namespace App\Livewire\Rfq;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    use Alert, WithLogging;

    public Request $request;

    public bool $canQuote = false;

    public function mount(Request $request): void
    {
        $this->request = $request->load([
            'buyer',
            'items',
            'quotes.supplier',
            'quotes.items.requestItem',
            'supplierInvitations.supplier',
        ]);

        $this->logPageView('RFQ Show', [
            'request_id' => $this->request->id,
        ]);

        $user = Auth::user();

        // Check if user has already submitted a quote
        $hasSubmittedQuote = $user && $this->request->quotes()
            ->where('supplier_id', $user->id)
            ->exists();

        $this->canQuote = $user
            && $user->id !== $this->request->buyer_id
            && !$hasSubmittedQuote
            && $this->request->status === 'open'
            && ($this->request->deadline === null || $this->request->deadline->isFuture());
    }

    public function render(): View
    {
        return view('livewire.rfq.show');
    }
}
